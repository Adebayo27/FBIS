<?php

namespace Tests\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Faker\Generator as Faker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PostControllerTests extends TestCase
{

    public function testPostIsCreatedSuccessfully()
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
        );
        $user = User::where('email', $email)->first();
        $token = JWTAuth::fromUser($user);
        // dd($token);
        $data = ["post" => "Hello"];
        $this->json('post', 'api/create-post', $data, ["Authorization" => "Bearer: ". $token])
        ->assertStatus(Response::HTTP_OK);
        
        
    }

   
}
