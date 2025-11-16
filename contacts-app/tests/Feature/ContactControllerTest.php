<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_contacts(): void
    {
        $group = Group::factory()->create(['name' => 'Friends']);
        Contact::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'group_id' => $group->id,
        ]);

        $response = $this->get('/contacts');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Friends');
    }
}
