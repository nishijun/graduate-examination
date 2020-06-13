<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
      parent::setUp();

      // テストユーザー作成
      $this->user = factory(User::class)->create();
    }

    public function test_should_登録済みのユーザーを認証して返却する() {
      $this->withoutMiddleware();
      $response = $this->json('POST', route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
      ]);
      $response->assertStatus(200)->assertJson(['name' => $this->user->name]);

      $this->assertAuthenticatedAs($this->user);
    }
}
