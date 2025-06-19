<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ApiController extends Controller
{
    public function check_username(Request $request){
        $username = $request->query('q');

        $exists = User::where("username", $username)->exists();

        return response()->json(["exists" => $exists]);
    }

    public  function check_email(Request $request){
        $email = $request->query('q');

        $exists = User::where("email", $email)->exists();

        return response()->json(["exists" => $exists]);
    }
}
