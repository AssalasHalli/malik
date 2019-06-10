<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            if ($request->password == $user->password && $user->valide == "OUI") {
                return response(json_encode(["message" => "OK", "data" => $user]), 201);
            } else {
                return response(json_encode(["message" => "Wrong password or user not valid", "code" => 10001, "data" => []]), 400);
            }
        } else {
            return response(json_encode(["message" => "User not found", "code" => 10001, "data" => []]), 400);
        }
    }

    public function register(Request $request)
    {
        try {
            $user = User::where("email", $request->email)->first();
            if (!$user) {
                $user = new User($request->all());
                if ($user->save()) {
                    return response(json_encode(["message" => "OK", "data" => $user->toJson()]), 201);
                }
            }
            return response(json_encode(["message" => "Bad Request", "code" => 10001, "data" => []]), 400);
        } catch (\Exception $e) {
            return response(json_encode(["message" => "Bad Request", "code" => 10001, "data" => $e->getMessage()]), 400);
        }
    }
}