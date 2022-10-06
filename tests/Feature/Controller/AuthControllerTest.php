<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Faker\Generator as Faker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserControllerTests extends TestCase
{

    // public function testIndexReturnsDataInValidFormat()
    // {

    //     $this->json('get', 'api/register')
    //         ->assertStatus(Response::HTTP_OK)
    //         ->assertJsonStructure(
    //             [

    //                 '*' => [
    //                     'id',
    //                     'name',
    //                     'email',
    //                     'email_verified_at',
    //                     'role',
    //                     'category_ids',
    //                     'otp',
    //                     'created_at',
    //                     'updated_at'
    //                 ]

    //             ]
    //         );
    // }

    public function testUserIsCreatedSuccessfully()
    {
        // $faker = new Faker();
        $faker = \Faker\Factory::create();
        $email = $faker->email();
        $payload = [
            'email' => $email,
            'password' => '123456',
        ];

        $this->json('post', 'api/register', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'status',
                    'user' => [
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                        'id'
                    ],
                    'message',
                    "authorization" => [
                        "token",
                        "type"
                    ]
                ]
            );
        // $this->assertDatabaseHas('users', $payload);
    }

    public function testUserLoginSuccessfully()
    {
        // $faker = new Faker();
        $faker = \Faker\Factory::create();
        $email = $faker->email();
        $payload = [
            'email' => $email,
            'password' => '123456',
        ];

        $this->json('post', 'api/register', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'status',
                    'user' => [
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                        'id'
                    ],
                    'message',
                    "authorization" => [
                        "token",
                        "type"
                    ]
                ]
            );;
        
        $this->json('post', 'api/login', $payload)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(
            [
                'status',
                'user' => [
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'id'
                ],
                "authorization" => [
                    "token",
                    "type"
                ]
            ]
        );;
        
    }

    public function testUserLogoutSuccessfully()
    {
        // $faker = new Faker();
        $faker = \Faker\Factory::create();
        $email = $faker->email();
        $payload = [
            'email' => $email,
            'password' => '123456',
        ];

        $this->json('post', 'api/register', $payload)
            ->assertStatus(Response::HTTP_CREATED);
        $user = User::where('email', $email)->first();
        $token = JWTAuth::fromUser($user);
        $this->json('post', 'api/login', $payload)
        ->assertStatus(Response::HTTP_OK);

        $this->json('post', 'api/logout', $payload, ['Authorization'=> 'Bearer' . $token])
        ->assertStatus(Response::HTTP_OK);
        
    }
}
