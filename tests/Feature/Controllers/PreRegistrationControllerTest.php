<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\PreRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PreRegistrationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_admin_can_view_pre_registrations()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)
            ->get(route('preregistrations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.preregistrations.index');
    }

    public function test_admin_can_approve_pre_registration()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $preRegistration = PreRegistration::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('preregistrations.approve', $preRegistration->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'status' => 'approved'
        ]);
    }

    public function test_admin_can_reject_pre_registration()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $preRegistration = PreRegistration::factory()->create([
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('preregistrations.reject', $preRegistration->id), [
                'rejection_reason' => 'Invalid documentation'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pre_registrations', [
            'id' => $preRegistration->id,
            'status' => 'rejected',
            'rejection_reason' => 'Invalid documentation'
        ]);
    }

    public function test_regular_user_cannot_access_pre_registrations()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)
            ->get(route('preregistrations.index'));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_pre_registrations()
    {
        $response = $this->get(route('preregistrations.index'));
        
        $response->assertRedirect(route('login'));
    }
}