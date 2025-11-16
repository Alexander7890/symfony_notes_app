<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_loads(): void
    {
        $response = $this->get('/contacts');
        $response->assertStatus(200);
    }

    public function test_contact_creation_flow(): void
    {
        $group = Group::factory()->create(['name' => 'Testing']);

        $response = $this->post('/contacts/new', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123',
            'note' => 'note',
            'groupId' => $group->id,
        ]);

        $response->assertRedirect('/contacts');
        $this->assertDatabaseHas('contacts', [
            'name' => 'Test User',
            'group_id' => $group->id,
        ]);
    }
}
