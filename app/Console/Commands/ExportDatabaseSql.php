<?php

namespace App\Console\Commands;

use App\Services\DatabaseExportService;
use Illuminate\Console\Command;

class ExportDatabaseSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export-sql {--output= : Custom destination path for the exported SQL file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export application database to a clean MySQL/MariaDB SQL dump for cPanel phpMyAdmin';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseExportService $exportService): int
    {
        $this->info('====================================================');
        $this->info(' Dzuels Foundation - MySQL Database Export Suite    ');
        $this->info('====================================================');
        $this->newLine();

        $customPath = $this->option('output');
        $targetPath = $customPath ? base_path($customPath) : database_path('dumps/dzuels_database.sql');

        $this->line("Target Output: <fg=cyan>{$targetPath}</>");
        $this->newLine();

        try {
            $result = $exportService->export($targetPath);

            $this->line('<fg=cyan;options=bold>Table Summary:</>');

            $totalRows = 0;
            $tableRows = [];

            foreach ($result['tables'] as $table => $rows) {
                $totalRows += $rows;
                $tableRows[] = [
                    'table' => $table,
                    'rows' => number_format($rows),
                    'status' => $rows > 0 ? '<fg=green>Dumped</>' : '<fg=gray>Schema only (0 rows)</>',
                ];
            }

            $this->table(['Table Name', 'Rows', 'Status'], $tableRows);

            $formattedSize = $this->formatBytes($result['bytes']);
            $tableCount = count($result['tables']);

            $this->newLine();
            $this->info("✔ Successfully exported {$tableCount} tables ({$totalRows} total rows) to:");
            $this->line("  <fg=bright-green;options=bold>{$result['path']}</> ({$formattedSize})");
            $this->newLine();
            $this->comment('This SQL file is ready for 1-click import via cPanel phpMyAdmin.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to export database: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Format file size in human-readable units.
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
