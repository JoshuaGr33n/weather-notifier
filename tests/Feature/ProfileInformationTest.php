<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_can_be_updated(): void
    {
        $this->actingAs($user = User::factory()->create());

        // Define the name you want to update the user to
        $newName = 'Test Name';
        $newEmail = 'test@example.com';

        // Send the update request
        $this->put('/user/profile-information', [
            'name' => $newName,
            'email' => $newEmail,
        ]);

        // Ensure the name and email have been updated as expected
        $this->assertEquals($newName, $user->fresh()->name);
        $this->assertEquals($newEmail, $user->fresh()->email);
    }
}
