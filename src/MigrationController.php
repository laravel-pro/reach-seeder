<?php


namespace LaravelPro\ReachSeeder;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class MigrationController extends Controller
{
    public function refresh()
    {
        $output = new BufferedOutput;
        Artisan::call('migrate:refresh', [], $output);
        return response($output->fetch());
    }
}