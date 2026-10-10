<?php

namespace Tests\Feature\Admin;

use App\Models\PartnerEnquiry;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryManagementTest extends TestCase
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

    public function test_guest_cannot_access_inquiry_management(): void
    {
        $volunteer = VolunteerApplication::create([
            'full_name' => 'Test Volunteer',
            'email' => 'volunteer@example.com',
            'phone' => '08011112222',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'status' => 'pending',
        ]);

        $partner = PartnerEnquiry::create([
            'organization_name' => 'Test Corp',
            'contact_name' => 'Test Rep',
            'email' => 'partner@example.com',
            'phone' => '08033334444',
            'partnership_type' => 'corporate_sponsor',
            'message' => 'Test proposal message',
            'status' => 'pending',
        ]);

        $this->get('/admin/inquiries')->assertRedirect('/admin/login');
        $this->patch("/admin/inquiries/volunteers/{$volunteer->id}", ['status' => 'reviewed'])->assertRedirect('/admin/login');
        $this->delete("/admin/inquiries/volunteers/{$volunteer->id}")->assertRedirect('/admin/login');
        $this->patch("/admin/inquiries/partners/{$partner->id}", ['status' => 'reviewed'])->assertRedirect('/admin/login');
        $this->delete("/admin/inquiries/partners/{$partner->id}")->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_inquiries_list_and_switch_tabs(): void
    {
        $volunteer = VolunteerApplication::create([
            'full_name' => 'Chioma Chukwu',
            'email' => 'chioma@example.com',
            'phone' => '08098765432',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'skills' => 'Python instruction and mentorship',
            'status' => 'pending',
        ]);

        $partner = PartnerEnquiry::create([
            'organization_name' => 'Ekiti Tech Hub Alliance',
            'contact_name' => 'Engr. Dapo Alabi',
            'email' => 'contact@ekititechhub.org',
            'phone' => '08022221111',
            'partnership_type' => 'school_partner',
            'message' => 'Collaboration proposal for digital lab setup in Ijero.',
            'status' => 'pending',
        ]);

        // Volunteers tab
        $responseVolunteers = $this->actingAs($this->admin)->get('/admin/inquiries?tab=volunteers');
        $responseVolunteers->assertOk();
        $responseVolunteers->assertSeeText('Chioma Chukwu');
        $responseVolunteers->assertSeeText('chioma@example.com');
        $responseVolunteers->assertSeeText('Python instruction');

        // Partners tab
        $responsePartners = $this->actingAs($this->admin)->get('/admin/inquiries?tab=partners');
        $responsePartners->assertOk();
        $responsePartners->assertSeeText('Ekiti Tech Hub Alliance');
        $responsePartners->assertSeeText('Engr. Dapo Alabi');
        $responsePartners->assertSeeText('School / Academic Partner');
    }

    public function test_admin_can_filter_inquiries_by_status_and_search(): void
    {
        VolunteerApplication::create([
            'full_name' => 'Pending Applicant',
            'email' => 'pending@example.com',
            'phone' => '08011111111',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'status' => 'pending',
        ]);

        VolunteerApplication::create([
            'full_name' => 'Contacted Applicant',
            'email' => 'contacted@example.com',
            'phone' => '08022222222',
            'age_band' => '18_to_55',
            'work_status' => 'employed',
            'status' => 'contacted',
        ]);

        // Filter by status=pending
        $responsePending = $this->actingAs($this->admin)->get('/admin/inquiries?tab=volunteers&status=pending');
        $responsePending->assertOk();
        $responsePending->assertSeeText('Pending Applicant');
        $responsePending->assertDontSeeText('Contacted Applicant');

        // Search query
        $responseSearch = $this->actingAs($this->admin)->get('/admin/inquiries?tab=volunteers&q=Contacted');
        $responseSearch->assertOk();
        $responseSearch->assertSeeText('Contacted Applicant');
        $responseSearch->assertDontSeeText('Pending Applicant');
    }

    public function test_admin_can_update_volunteer_application_status(): void
    {
        $volunteer = VolunteerApplication::create([
            'full_name' => 'Tunde Bakare',
            'email' => 'tunde@example.com',
            'phone' => '08055554444',
            'age_band' => '18_to_55',
            'work_status' => 'self_employed',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch("/admin/inquiries/volunteers/{$volunteer->id}", [
                'status' => 'contacted',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('contacted', $volunteer->fresh()->status);
    }

    public function test_admin_can_delete_volunteer_application(): void
    {
        $volunteer = VolunteerApplication::create([
            'full_name' => 'Temporary Volunteer',
            'email' => 'temp@example.com',
            'phone' => '08099990000',
            'age_band' => 'below_18',
            'work_status' => 'student',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/admin/inquiries/volunteers/{$volunteer->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('volunteer_applications', ['id' => $volunteer->id]);
    }

    public function test_admin_can_update_partner_enquiry_status(): void
    {
        $partner = PartnerEnquiry::create([
            'organization_name' => 'Global Aid Foundation',
            'contact_name' => 'Sarah Connor',
            'email' => 'sarah@globalaid.org',
            'phone' => '08077778888',
            'partnership_type' => 'equipment_donor',
            'message' => 'Donation of 25 refurbished laptops for rural schools.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch("/admin/inquiries/partners/{$partner->id}", [
                'status' => 'reviewed',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('reviewed', $partner->fresh()->status);
    }

    public function test_admin_can_delete_partner_enquiry(): void
    {
        $partner = PartnerEnquiry::create([
            'organization_name' => 'Spam Partner LLC',
            'contact_name' => 'Spammer',
            'email' => 'spam@partner.com',
            'partnership_type' => 'other',
            'message' => 'Commercial sales proposal',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/admin/inquiries/partners/{$partner->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('partner_enquiries', ['id' => $partner->id]);
    }
}
