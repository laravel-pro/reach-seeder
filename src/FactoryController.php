<?php


namespace LaravelPro\ReachSeeder;


use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FactoryController extends Controller
{
    protected function builder(Request $request)
    {
        $class = $request->input('model');
        $amount = $request->input('amount');

        /** @var EloquentFactory $factory */
        $factory = app(EloquentFactory::class);

        if (!$factory->offsetExists($class)) {
            throw new FactoryNotFoundException();
        }

        $builder = $factory->of($class);

        if (isset($amount) && is_int($amount)) {
            $builder->times($amount);
        }

        return $builder;
    }

    public function make(Request $request)
    {
        return $this->builder($request)->make();
    }

    public function create(Request $request)
    {
        return $this->builder($request)->create();
    }
}