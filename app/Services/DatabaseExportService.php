<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DatabaseExportService
{
    /**
     * Tables to exclude from SQL dump (system or internal tables).
     *
     * @var array<int, string>
     */
    protected array $excludedTables = [
        'sqlite_sequence',
        'sqlite_stat1',
    ];

    /**
     * Specific priority table ordering to respect foreign key hierarchies.
     * Independent parent tables first, followed by child tables.
     *
     * @var array<int, string>
     */
    protected array $preferredOrder = [
        'migrations',
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'settings',
        'team_members',
        'partners',
        'programmes',
        'events',
        'event_sponsors',
        'event_registrations',
        'gallery_albums',
        'gallery_photos',
        'volunteer_applications',
        'partner_enquiries',
        'contact_messages',
        'posts',
        'newsletter_subscribers',
        'page_sections',
    ];

    /**
     * Export the database into a clean MySQL/MariaDB SQL file.
     *
     * @param  string|null  $outputPath  Absolute or relative path to the destination SQL file.
     * @return array{path: string, bytes: int, tables: array<string, int>}
     */
    public function export(?string $outputPath = null): array
    {
        $targetPath = $outputPath ?: database_path('dumps/dzuels_database.sql');

        // Ensure target directory exists
        $dir = dirname($targetPath);
        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $tables = $this->getOrderedTables();
        $tableStats = [];

        $handle = fopen($targetPath, 'wb');
        if (! $handle) {
            throw new \RuntimeException("Unable to open output file for writing: {$targetPath}");
        }

        // 1. Write Header
        fwrite($handle, $this->getHeaderSql());

        // 2. Write DDL & DML for each table
        foreach ($tables as $table) {
            $ddl = $this->generateTableDdl($table);
            fwrite($handle, $ddl."\n\n");

            $rowCount = DB::table($table)->count();
            $tableStats[$table] = $rowCount;

            $dml = $this->dumpTableData($table, $rowCount);
            fwrite($handle, $dml."\n\n");
        }

        // 3. Write Footer
        fwrite($handle, $this->getFooterSql());
        fclose($handle);

        $bytes = (int) filesize($targetPath);

        return [
            'path' => $targetPath,
            'bytes' => $bytes,
            'tables' => $tableStats,
        ];
    }

    /**
     * Get sorted list of tables to dump, prioritizing parent tables.
     *
     * @return array<int, string>
     */
    public function getOrderedTables(): array
    {
        $allTables = [];

        foreach (Schema::getTables() as $tableObj) {
            $name = is_array($tableObj) ? $tableObj['name'] : ($tableObj->name ?? (string) $tableObj);
            if (! in_array($name, $this->excludedTables, true)
                && ! str_starts_with($name, 'sqlite_')
                && ! str_starts_with($name, 'pma_')
                && ! str_starts_with($name, 'pma__')) {
                $allTables[] = $name;
            }
        }

        // Order according to preferredOrder, appending any unanticipated tables at the end
        $ordered = [];
        foreach ($this->preferredOrder as $table) {
            if (in_array($table, $allTables, true)) {
                $ordered[] = $table;
            }
        }

        foreach ($allTables as $table) {
            if (! in_array($table, $ordered, true)) {
                $ordered[] = $table;
            }
        }

        return $ordered;
    }

    /**
     * Generate MySQL CREATE TABLE DDL for a table.
     */
    public function generateTableDdl(string $table): string
    {
        $columns = Schema::getColumns($table);
        $indexes = Schema::getIndexes($table);
        $foreignKeys = Schema::getForeignKeys($table);

        $lines = [];

        // Columns definition
        foreach ($columns as $column) {
            $colName = "`{$column['name']}`";
            $colType = $this->mapColumnType($column);
            $nullability = $column['nullable'] ? 'NULL' : 'NOT NULL';
            $default = $this->formatDefaultValue($column);
            $extra = $column['auto_increment'] ? 'AUTO_INCREMENT' : '';

            $parts = array_filter([$colName, $colType, $nullability, $default, $extra]);
            $lines[] = '  '.implode(' ', $parts);
        }

        // Indexes definition
        $processedIndexes = [];
        foreach ($indexes as $index) {
            $cols = implode(', ', array_map(fn ($c) => "`{$c}`", $index['columns']));

            if (! empty($index['primary'])) {
                $lines[] = "  PRIMARY KEY ({$cols})";

                continue;
            }

            $idxName = $index['name'];
            if (in_array($idxName, $processedIndexes, true)) {
                continue;
            }
            $processedIndexes[] = $idxName;

            if (! empty($index['unique'])) {
                $lines[] = "  UNIQUE KEY `{$idxName}` ({$cols})";
            } else {
                $lines[] = "  KEY `{$idxName}` ({$cols})";
            }
        }

        // Foreign keys definition
        foreach ($foreignKeys as $fk) {
            $cols = implode(', ', array_map(fn ($c) => "`{$c}`", $fk['columns']));
            $foreignCols = implode(', ', array_map(fn ($c) => "`{$c}`", $fk['foreign_columns']));
            $foreignTable = "`{$fk['foreign_table']}`";
            $fkName = $fk['name'] ?: "fk_{$table}_".implode('_', $fk['columns']);

            $fkSql = "  CONSTRAINT `{$fkName}` FOREIGN KEY ({$cols}) REFERENCES {$foreignTable} ({$foreignCols})";

            if (! empty($fk['on_delete']) && strtolower($fk['on_delete']) !== 'no action') {
                $fkSql .= ' ON DELETE '.strtoupper($fk['on_delete']);
            }
            if (! empty($fk['on_update']) && strtolower($fk['on_update']) !== 'no action') {
                $fkSql .= ' ON UPDATE '.strtoupper($fk['on_update']);
            }

            $lines[] = $fkSql;
        }

        $body = implode(",\n", $lines);

        $sql = "--\n";
        $sql .= "-- Table structure for table `{$table}`\n";
        $sql .= "--\n\n";
        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sql .= "CREATE TABLE `{$table}` (\n";
        $sql .= $body."\n";
        $sql .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        return $sql;
    }

    /**
     * Map database column definitions to MySQL types.
     *
     * @param  array<string, mixed>  $column
     */
    protected function mapColumnType(array $column): string
    {
        $name = $column['name'];
        $typeName = strtolower($column['type_name'] ?? '');
        $type = strtolower($column['type'] ?? '');
        $isAutoIncrement = (bool) ($column['auto_increment'] ?? false);

        if ($isAutoIncrement) {
            return 'bigint unsigned';
        }

        if (str_ends_with($name, '_id') && in_array($typeName, ['integer', 'int', 'bigint'], true)) {
            return 'bigint unsigned';
        }

        return match ($typeName) {
            'tinyint' => 'tinyint(1)',
            'smallint' => 'smallint',
            'mediumint' => 'mediumint',
            'integer', 'int' => in_array($name, ['sort_order', 'batch', 'attempts', 'total_jobs', 'pending_jobs', 'failed_jobs', 'votes'], true) ? 'int' : 'bigint unsigned',
            'bigint' => 'bigint unsigned',
            'float' => 'float',
            'double' => 'double',
            'decimal', 'numeric' => 'decimal(10,2)',
            'boolean', 'bool' => 'tinyint(1)',
            'datetime' => 'datetime',
            'timestamp' => 'timestamp',
            'date' => 'date',
            'time' => 'time',
            'text' => in_array($name, ['body', 'description', 'payload', 'exception', 'data', 'meta_data', 'schedule'], true) ? 'longtext' : 'text',
            'longtext' => 'longtext',
            'json' => 'longtext',
            'varchar', 'string', 'char' => $this->resolveVarcharLength($name, $type),
            default => 'varchar(255)',
        };
    }

    /**
     * Resolve varchar length.
     */
    protected function resolveVarcharLength(string $name, string $type): string
    {
        if (preg_match('/(?:varchar|char)\((\d+)\)/', $type, $matches)) {
            return "varchar({$matches[1]})";
        }

        if ($name === 'remember_token') {
            return 'varchar(100)';
        }

        if (in_array($name, ['email', 'password', 'token', 'slug', 'name', 'title'], true)) {
            return 'varchar(255)';
        }

        return 'varchar(255)';
    }

    /**
     * Format SQL default value for a column.
     *
     * @param  array<string, mixed>  $column
     */
    protected function formatDefaultValue(array $column): ?string
    {
        $default = $column['default'] ?? null;

        if ($default === null) {
            if ($column['nullable'] && ! ($column['auto_increment'] ?? false)) {
                return 'DEFAULT NULL';
            }

            return null;
        }

        // Clean surrounding quotes or SQLite wrappers
        $trimmed = trim((string) $default);
        if ($trimmed === 'NULL' || $trimmed === 'null') {
            return 'DEFAULT NULL';
        }

        if (preg_match("/^'(.*)'$/", $trimmed, $matches)) {
            $trimmed = $matches[1];
        }

        $typeName = strtolower($column['type_name'] ?? '');
        if (in_array($typeName, ['tinyint', 'integer', 'int', 'bigint', 'float', 'double', 'decimal'], true)) {
            if (is_numeric($trimmed)) {
                return "DEFAULT {$trimmed}";
            }
        }

        $normalizedFunc = strtoupper(trim($trimmed, "()'\" "));
        if (in_array($normalizedFunc, ['CURRENT_TIMESTAMP', 'NOW', 'LOCALTIME', 'LOCALTIMESTAMP'], true)) {
            return 'DEFAULT CURRENT_TIMESTAMP';
        }

        $escaped = $this->escapeString($trimmed);

        return "DEFAULT '{$escaped}'";
    }

    /**
     * Dump table rows as batch SQL INSERT statements.
     */
    public function dumpTableData(string $table, int $rowCount): string
    {
        if ($rowCount === 0) {
            return "-- Dumping data for table `{$table}`: 0 rows (empty)";
        }

        $sql = "--\n";
        $sql .= "-- Dumping data for table `{$table}` ({$rowCount} rows)\n";
        $sql .= "--\n\n";
        $sql .= "LOCK TABLES `{$table}` WRITE;\n";
        $sql .= "/*!40000 ALTER TABLE `{$table}` DISABLE KEYS */;\n";

        // Query all rows
        $rows = DB::table($table)->get();
        if ($rows->isEmpty()) {
            $sql .= "/*!40000 ALTER TABLE `{$table}` ENABLE KEYS */;\n";
            $sql .= 'UNLOCK TABLES;';

            return $sql;
        }

        $firstRow = (array) $rows->first();
        $columns = array_keys($firstRow);
        $quotedColumns = implode(', ', array_map(fn ($c) => "`{$c}`", $columns));

        // Group rows in batches of 50
        $chunks = $rows->chunk(50);
        foreach ($chunks as $chunk) {
            $valueRows = [];
            foreach ($chunk as $row) {
                $values = [];
                foreach ((array) $row as $val) {
                    $values[] = $this->formatSqlValue($val);
                }
                $valueRows[] = '('.implode(', ', $values).')';
            }

            $sql .= "INSERT INTO `{$table}` ({$quotedColumns}) VALUES\n".implode(",\n", $valueRows).";\n";
        }

        $sql .= "/*!40000 ALTER TABLE `{$table}` ENABLE KEYS */;\n";
        $sql .= 'UNLOCK TABLES;';

        return $sql;
    }

    /**
     * Format a PHP value into a safe MySQL literal value.
     */
    public function formatSqlValue(mixed $value): string
    {
        if (is_null($value)) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return (string) $value;
        }

        return "'".$this->escapeString((string) $value)."'";
    }

    /**
     * Escape special characters in strings for safe MySQL dump inclusion.
     */
    public function escapeString(string $value): string
    {
        return str_replace(
            ['\\', "\0", "\n", "\r", "'", "\x1a"],
            ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\Z'],
            $value
        );
    }

    /**
     * Get the MySQL file header.
     */
    protected function getHeaderSql(): string
    {
        $timestamp = gmdate('Y-m-d H:i:s');
        $laravelVersion = app()->version();
        $phpVersion = PHP_VERSION;

        return <<<SQL
-- =====================================================================
-- Dzuels Educational Foundation - Production Database Export
-- Target: cPanel phpMyAdmin / MySQL 5.7+ / MariaDB 10.3+
-- Generated: {$timestamp} UTC
-- Laravel: {$laravelVersion} | PHP: {$phpVersion}
-- =====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


SQL;
    }

    /**
     * Get the MySQL file footer.
     */
    protected function getFooterSql(): string
    {
        return <<<'SQL'

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS=1;

-- =====================================================================
-- End of Database Export
-- =====================================================================

SQL;
    }
}
