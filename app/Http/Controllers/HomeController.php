<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        if (!session('id')) {
            return redirect()->route('login');
        }
        return view('index');
    }

    public function profiloUtente(){
        if (!session('id')){
            error_log("Accesso non autorizzato: utente non autenticato.");
            return redirect('/login');
        }
        $user_id = session('id');

        $info = DB::table('utenti')
                        ->select('username', 'avatar', 'bio', 'data_registrazione', 'email')
                        ->where('id', $user_id)
                        ->first();

        $commentiTotali = DB::table('commenti')
                            ->where('user_id', $user_id)
                            ->count();

        if (empty($info) && $commentiTotali === 0) {
            error_log("Nessun profilo trovato per l'utente con ID: {$user_id}");
            return redirect('/login');
        }

        return view('profilo_utente', [
            'info' => $info,
            'commentiTotali' => $commentiTotali
        ]);
    }

    public function interazioniPost(){
        if(!session('id')){
            return redirect()->route('login');
        }
        return view('interazioni_post');
    }

    public function informazioniPagina(){
        if(!session('id')){
            return redirect()->route('login');
        }
        return view('/informazioni');
    }
}