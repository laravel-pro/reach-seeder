<?php


namespace LaravelPro\ReachSeeder\Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelPro\ReachSeeder\Tests\Fixtures\User;
use LaravelPro\ReachSeeder\Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $this->withFactories(__DIR__.'/../Fixtures/factories');
        $this->loadMigrationsFrom(__DIR__.'/../Fixtures/migrations');
        $this->app['config']->set('auth.providers.users.model', User::class);
    }

    public function testLoginWithUserId()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $this->postJson('/api/reach-seeder/login', ['id' => $user->id])
            ->assertSuccessful()
            ->assertJsonFragment(['message' => 'login success with id 1']);

        $this->assertAuthenticatedAs($user);
    }

    public function testLoginFailWhenUserIdNotExistsInDB()
    {
        $this->withoutExceptionHandling();

        $this->postJson('/api/reach-seeder/login', ['id' => 1])
            ->assertStatus(400)
            ->assertJsonFragment(['message' => 'login failed with user id 1']);
    }
}
