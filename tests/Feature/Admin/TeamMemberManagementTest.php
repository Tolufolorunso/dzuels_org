<?php

namespace Tests\Feature\Admin;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TeamMemberManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@dzuelsfoundation.org',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_team_management(): void
    {
        $response = $this->get('/admin/team');
        $response->assertRedirect('/admin/login');

        $responseCreate = $this->get('/admin/team/create');
        $responseCreate->assertRedirect('/admin/login');

        $responsePost = $this->post('/admin/team', []);
        $responsePost->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_team_members_list(): void
    {
        $member = TeamMember::create([
            'name' => 'Bolaji Agbelusi',
            'role' => 'Trustee',
            'group' => 'trustee',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/team');

        $response->assertOk();
        $response->assertSeeText('Bolaji Agbelusi');
        $response->assertSeeText('Trustee');
    }

    public function test_admin_can_filter_team_members_by_group(): void
    {
        TeamMember::create([
            'name' => 'Trustee One',
            'role' => 'Trustee',
            'group' => 'trustee',
        ]);
        TeamMember::create([
            'name' => 'Advisor One',
            'role' => 'Advisor',
            'group' => 'advisor',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/team?group=trustee');

        $response->assertOk();
        $response->assertSeeText('Trustee One');
        $response->assertDontSeeText('Advisor One');
    }

    public function test_admin_can_create_team_member_with_photo(): void
    {
        $photo = UploadedFile::fake()->image('member.jpg', 200, 200);

        $response = $this->actingAs($this->admin)->post('/admin/team', [
            'name' => 'Dr. Jane Doe',
            'role' => 'Advisor',
            'group' => 'advisor',
            'field' => 'Medical Services',
            'bio' => 'Experienced healthcare practitioner.',
            'linkedin_url' => 'https://www.linkedin.com/in/janedoe',
            'sort_order' => 5,
            'is_active' => '1',
            'photo' => $photo,
        ]);

        $response->assertRedirect(route('admin.team.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('team_members', [
            'name' => 'Dr. Jane Doe',
            'group' => 'advisor',
            'field' => 'Medical Services',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $created = TeamMember::where('name', 'Dr. Jane Doe')->first();
        $this->assertNotNull($created->photo);
        $this->assertTrue(File::exists(public_path($created->photo)));

        // Clean up uploaded test file
        if (File::exists(public_path($created->photo))) {
            File::delete(public_path($created->photo));
        }
    }

    public function test_team_member_validation_fails_for_invalid_data(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/team', [
            'name' => '',
            'role' => '',
            'group' => 'invalid-group',
            'linkedin_url' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors(['name', 'role', 'group', 'linkedin_url']);
    }

    public function test_admin_can_update_team_member(): void
    {
        $member = TeamMember::create([
            'name' => 'Old Name',
            'role' => 'Trustee',
            'group' => 'trustee',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/team/{$member->id}", [
            'name' => 'New Name',
            'role' => 'Senior Trustee',
            'group' => 'trustee',
            'sort_order' => 2,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.team.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('team_members', [
            'id' => $member->id,
            'name' => 'New Name',
            'role' => 'Senior Trustee',
            'sort_order' => 2,
        ]);
    }

    public function test_admin_can_toggle_team_member_status(): void
    {
        $member = TeamMember::create([
            'name' => 'Toggle Member',
            'role' => 'Trustee',
            'group' => 'trustee',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/team/{$member->id}/toggle");

        $response->assertRedirect();
        $this->assertDatabaseHas('team_members', [
            'id' => $member->id,
            'is_active' => false,
        ]);

        $this->actingAs($this->admin)->patch("/admin/team/{$member->id}/toggle");
        $this->assertDatabaseHas('team_members', [
            'id' => $member->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_team_member(): void
    {
        $member = TeamMember::create([
            'name' => 'Delete Member',
            'role' => 'Trustee',
            'group' => 'trustee',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/team/{$member->id}");

        $response->assertRedirect(route('admin.team.index'));
        $this->assertDatabaseMissing('team_members', [
            'id' => $member->id,
        ]);
    }
}
