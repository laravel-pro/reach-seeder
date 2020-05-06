<?php


namespace LaravelPro\ReachSeeder;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $id = $request->input('id');
        if (auth()->loginUsingId($id)) {
            return ['message' => "login success with id {$id}"];
        }
        return response()->json(['message' => "login failed with user id {$id}"], 400);
    }
}