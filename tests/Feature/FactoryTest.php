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

    /**
     * @dataProvider urlsProvider
     * @param string $url
     */
    public function test_it_could_call_from_api(string $url)
    {
        $this->postJson($url, [
            'model' => User::class,
        ])
            ->assertSuccessful()
            ->assertJsonStructure(self::ExpectUserStructure);
    }

    /**
     * @dataProvider urlsProvider
     * @param string $url
     */
    public function test_multi_model_created_when_pass_amount(string $url)
    {
        $this->postJson($url, [
            'model' => User::class,
            'amount' => 2,
        ])
            ->assertSuccessful()
            ->assertJsonStructure([self::ExpectUserStructure]);
    }

    /**
     * @dataProvider urlsProvider
     * @param string $url
     */
    public function test_it_throw_not_found_when_model_not_exists(string $url)
    {
        $this->expectException(NotFoundHttpException::class);
        $this->postJson('$url', [
            'model' => 'App\User',
        ]);
    }

    public function test_it_should_seed_database_and_return()
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

    public function urlsProvider()
    {
        return [
            '[MAKE]' => ['/reach-seeder/model/make'],
            '[CREATE]' => ['/reach-seeder/model/create'],
        ];
    }
}
