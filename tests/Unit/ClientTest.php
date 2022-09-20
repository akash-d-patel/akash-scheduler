<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\User;
use Tests\TestCase;

class ClientTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /**
     * All Brands 
     * @return void
     */
    public function test_getting_the_clients()
    {
        $response = $this->get('api/clients');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function test_add_client_required_name_validation()
    {
        $response = $this->post('api/clients');
        $response->assertSessionHasErrors(['name']);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function test_add_client_name_blank_validation()
    {
        $response = $this->post('api/clients', [
            "name" => '',
        ]);
        $errors = session(['name' => 'please provide the name']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_add_client_active()
    {
        $response = $this->post('api/clients', [
            "name" => 'Add Client',
            "status" => 'Active'
        ]);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function test_add_client_inactive()
    {
        $response = $this->post('api/clients',[
            "name" => 'Add Client',
            "status" => 'Inactive'
        ]);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function test_update_client_required_name_validation()
    {
        $client = Client::factory()->create();
        $response = $this->put('api/clients/' . $client->id);
        $response->assertStatus(302);
        $errors = session(['name' => 'Please provide the client name']);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_update_client_blank_name_validation()
    {
        $client = Client::factory()->create();
        $response = $this->put('api/clients/' . $client->id, [
            "name" => '',
        ]);
        $errors = session(['name' => 'please provide the name']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * Client Details
     * @test
     */
    public function test_getting_the_specific_client_details()
    {
        $client = Client::factory()->create();
        $response = $this->get('api/clients/' . $client->id);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    /**
     *Client Delete 
     * @test
     */
    public function test_delete_client_success()
    {
        $client = Client::factory()->create();
        $response = $this->delete('api/clients/' . $client->id);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }


}
