<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller{

    public function get_register(){
        if(session("username")){
            return redirect("/index");
        }
        return view("register");
    }

    public function post_register(Request $request){
        if(session("username")){
            return redirect("/index");
        }

        $request->merge(['email' => strtolower($request->input('email'))]);

        // Validazione dati
        $request->validate([
            "username" => ["required", "string", "min:5", "max:15", "unique:utenti,username", "regex:/^(?!_)(?!.*__)[a-zA-Z0-9_]+(?<!_)$/"],
            "email" => ["required", "email", "unique:utenti,email"],
            "password" => ["required", "confirmed",
            Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        $avatar_path = 'assets/images/avatar-utenti/';
        $avatar_disponibili = glob(public_path($avatar_path) . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        $percorso_avatar_scelto = 'assets/images/avatar.png';
        if(!empty($avatar_disponibili)){
            $random_key = $avatar_disponibili[array_rand($avatar_disponibili)];
            $percorso_avatar_scelto = str_replace(public_path() . '/', '', $random_key);
        }
        // Creazione utente
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_BCRYPT);
        $user->avatar = $percorso_avatar_scelto;
        $user->save();
        session(["id" => $user->id, "username" => $user->username, "email" => $user->email, "admin" => $user->is_admin]);
        return redirect("/index");
    }


    public function get_login(){
        if(session("username")){
            return redirect("/index");
        }
        return view("login");
    }
    
    public function post_login(Request $request){
        if(session("username")){
            return redirect("/index");
        }
        
        $request->validate([
            "username" => ["required", "string"],
            "password" => ["required"]
        ]);
        
        $user = User::where("username","=", $request->username)->first();
        if(!$user || !password_verify($request->password, $user->password)){
            return redirect("/login")->withErrors(["username" => "Username o password errati."])->withInput();
        }
        
        session(["id" => $user->id, "username" => $user->username, "email" => $user->email, "admin" => $user->is_admin]);
        return redirect("/index");
    }
    
    public function logout(){
        session()->flush();
        return redirect("/login");
    }
}