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

    public function testCreatingModel()
    {
        $this->postJson('/reach-seeder/model/make', ['model' => User::class])
            ->assertSuccessful()
            ->assertJsonStructure(self::ExpectUserStructure);

        $this->postJson('/reach-seeder/model/create', ['model' => User::class])
            ->assertSuccessful()
            ->assertJsonStructure(self::ExpectUserStructure);
    }

    public function testCreatingCollectionOfModel()
    {
        $this->postJson('/reach-seeder/model/make', ['model' => User::class, 'amount' => 2])
            ->assertJsonCount(2)
            ->assertJsonStructure([self::ExpectUserStructure]);

        $response = $this->postJson('/reach-seeder/model/create', ['model' => User::class, 'amount' => 2])
            ->assertJsonCount(2)
            ->assertJsonStructure([self::ExpectUserStructure]);

        $createdUsers = $response->json();

        $this->assertDatabaseHas('users', [
            'id' => $createdUsers[0]['id'],
            'name' => $createdUsers[0]['name'],
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $createdUsers[1]['id'],
            'name' => $createdUsers[1]['name'],
        ]);
    }

    public function testThrowingNotFoundWhenModelNotDefined()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->postJson('/reach-seeder/model/make', ['model' => 'App\User']);
    }

    public function testMakingModelOverridingAttributes()
    {
        $response = $this->postJson('/reach-seeder/model/make', [
            'model' => User::class,
            'attributes' => [
                'name' => 'Mr. Strange',
            ],
        ]);

        $createdUser = $response->json();

        $this->assertEquals('Mr. Strange', $createdUser['name']);
    }

    public function testCreatingModelOverridingAttributes()
    {
        $response = $this->postJson('/reach-seeder/model/create', [
            'model' => User::class,
            'attributes' => [
                'name' => 'Mr. Strange',
            ],
        ]);

        $createdUser = $response->json();

        $this->assertEquals('Mr. Strange', $createdUser['name']);
        $this->assertDatabaseHas('users', ['name' => 'Mr. Strange']);
    }
}
