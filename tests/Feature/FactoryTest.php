<?php


namespace LaravelPro\ReachSeeder\Tests\Feature;


use LaravelPro\ReachSeeder\Tests\Fixtures\User;
use LaravelPro\ReachSeeder\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FactoryTest extends TestCase
{
    const ExpectUserStructure = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $this->withFactories(__DIR__.'/../Fixtures/factories');
        $this->loadMigrationsFrom(__DIR__.'/../Fixtures/migrations');
    }

    public function test_factory_make_can_by_call_from_api()
    {
        $this->postJson('/reach-seeder/model/make', [
            'model' => User::class,
        ])
            ->assertSuccessful()
            ->assertJsonStructure(self::ExpectUserStructure);
    }

    public function test_factory_make_multi_model_when_pass_amount()
    {
        $this->postJson('/reach-seeder/model/make', [
            'model' => User::class,
            'amount' => 2,
        ])
            ->assertSuccessful()
            ->assertJsonStructure([self::ExpectUserStructure]);
    }

    public function test_factory_make_throw_not_found_when_model_not_exists()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->postJson('/reach-seeder/model/make', [
            'model' => 'App\User',
        ]);
    }

    public function test_factory_create_seed_database_and_return()
    {
        $response = $this->postJson('/reach-seeder/model/create', [
            'model' => User::class,
        ])
            ->assertSuccessful()
            ->assertJsonStructure(self::ExpectUserStructure);

        $data = $response->json();

        $this->assertDatabaseHas('users', [
            'id' => $data['id'],
            'name' => $data['name'],
        ]);
    }

    public function test_factory_create_multi_model_when_pass_amount()
    {
        $response = $this->postJson('/reach-seeder/model/create', [
            'model' => User::class,
            'amount' => 2,
        ])
            ->assertSuccessful()
            ->assertJsonStructure([self::ExpectUserStructure]);

        $data = $response->json();

        foreach ($data as $user) {
            $this->assertDatabaseHas('users', [
                'id' => $user['id'],
                'name' => $user['name'],
            ]);
        }
    }
}
