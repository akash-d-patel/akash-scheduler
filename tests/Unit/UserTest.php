<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Client;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Response;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_can_create_user()
    {
        $response = $this->get('api/users');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function test_user_client_id_required_validation()
    {
        $client = [
            'client_id' => '',
        ];
        $response = $this->post('api/users', $client);
        $response->assertStatus(302);
    }


    /**
     * @test
     */
    public function test_add_user_required_validation()
    {
        $response = $this->post('api/users');
        $response->assertSessionHasErrors(['name']);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function test_add_user_name_blank_validation()
    {
        $response = $this->post('api/users', [
            "name" => '',
        ]);
        $errors = session(['name' => 'please provide the name']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_add_user_required_email_validation()
    {
        $response = $this->post('api/users');
        $errors = session(['email']);
        $response->assertSessionHasErrors($errors);
        $response->assertStatus(302);
    }

    /**
     * @test
     */

    public function test_add_user_email_blank_validation()
    {
        $response = $this->post('api/users', [
            "email" => ''
        ]);
        $errors = session(['email' => 'Please provide the user email']);
        $response->assertSessionHasErrors($errors)->assertStatus(302);
    }

    /**
     * @test
     */
    public function test_add_user_required_password_validation()
    {
        $response = $this->post('api/users');
        $errors = session(['password']);
        $response->assertSessionHasErrors($errors);
        $response->assertStatus(302);
    }

    /**
     * @test
     */

    public function test_add_user_password_blank_validation()
    {
        $response = $this->post('api/users', [
            "password" => ''
        ]);
        $errors = session(['password' => 'Please provide the password']);
        $response->assertSessionHasErrors($errors)->assertStatus(302);
    }

    /**
     * @test
     */

    public function test_user_empty_array_validation()
    {
        $user = [];
        $response = $this->post('api/users');
        $response->assertSessionHasErrors($user);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function test_add_user_name_unique_validation()
    {

        $user = User::factory()->create();

        $response = $this->post('api/users', [
            "name" => $user->name,
            "email" => $user->email,
            "password" =>  $user->password,
            "password_confirmation" => $user->password_confirmation,
            "status" => $user->status
        ]);

        $response->assertStatus(302);
        $errors = session(["name" => "The user name has already been taken."]);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_add_user_email_unique_validation()
    {

        $user = User::factory()->create();

        $response = $this->post('api/users', [
            "name" => $user->name,
            "email" => $user->email,
            "password" =>  $user->password,
            "password_confirmation" => $user->password_confirmation,
            "status" => $user->status
        ]);

        $response->assertStatus(302);
        $errors = session(["email" => "The email must be a valid email."]);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_add_user_password_unique_validation()
    {

        $user = User::factory()->create();

        $response = $this->post('api/users', [
            "name" => $user->name,
            "email" => $user->email,
            "password" =>  $user->password,
            "password_confirmation" => $user->password_confirmation,
            "status" => $user->status
        ]);

        $response->assertStatus(302);
        $errors = session(["password" => "The password must be at least 6 characters."]);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_add_user_password_confirmation_unique_validation()
    {

        $user = User::factory()->create();

        $response = $this->post('api/users', [
            "name" => $user->name,
            "email" => $user->email,
            "password" =>  $user->password,
            "password_confirmation" => $user->password_confirmation,
            "status" => $user->status
        ]);

        $response->assertStatus(302);
        $errors = session(["password_confirmation" => "The password and password confirmation must match"]);
        $response->assertSessionHasErrors($errors);
    }

    /**
     *
     * @test
     */
    public function test_update_user_required_validation()
    {
        $user = User::factory()->create();

        $response = $this->put('api/users/' . $user->id);

        $response->assertStatus(302);
        $errors = session(['name' => 'Please provide the user name']);
        $response->assertSessionHasErrors($errors);
    }


    /**
     *
     * @test
     */
    public function test_update_user_blank_validation()
    {
        $user = User::factory()->create();

        $response = $this->put('api/users/' . $user->id, [
            "name" => '',
        ]);
        $errors = session(['name' => 'please provide the name']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errors);
    }

    /**
     *
     * @test
     */
    public function test_update_user_email_required_validation()
    {
        $user = User::factory()->create();

        $response = $this->put('api/users/' . $user->id);

        $response->assertStatus(302);
        $errors = session(['email' => 'Please provide the user email']);
        $response->assertSessionHasErrors($errors);
    }

    /**
     *
     * @test
     */
    public function test_update_user_email_blank_validation()
    {
        $user = User::factory()->create();

        $response = $this->put('api/users/' . $user->id, [
            "email" => '',
        ]);
        $errors = session(['email' => 'Please provide the user email']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errors);
    }

    /**
     *
     * @test
     */
    public function test_update_user_password_required_validation()
    {
        $user = User::factory()->create();

        $response = $this->put('api/users/' . $user->id);

        $response->assertStatus(302);
        $errors = session(['password' => 'Please provide the password']);
        $response->assertSessionHasErrors($errors);
    }

    /**
     *
     * @test
     */
    public function test_update_user_password_blank_validation()
    {
        $user = User::factory()->create();

        $response = $this->put('api/users/' . $user->id, [
            "password" => '',
        ]);
        $errors = session(['password' => 'Please provide the password']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @test
     */
    public function test_update_blank_status_validation()
    {
        $user = User::factory()->create();
        $response = $this->put('api/users/' . $user->id, [
            "status" => ''
        ]);
        $response->assertStatus(302);
        $errors = session(['status' => 'please selected status']);
        $response->assertSessionHasErrors($errors);
    }

    /**
     *  User Details
     * @test
     */
    public function test_getting_the_specific_user_details()
    {
        $user = User::factory()->create();

        $response = $this->get('api/users/' . $user->id);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function test_delete_user_success()
    {
        $user = User::factory()->create();
        $response = $this->delete('api/users/' . $user->id);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }
}
