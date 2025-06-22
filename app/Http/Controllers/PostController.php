<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function recuperaPostSingolo($reddit_id){
        if(!session('id')){
            error_log("Accesso non autorizzato al post singolo: utente non autenticato.");
        }

        $user_id = session('id');

        $votoSubquery = DB::table('voti_utenti as v')
                            ->select('v.tipo_voto')
                            ->where('v.user_id', $user_id)
                            ->whereColumn('v.post_id', 'p.id')
                            ->limit(1);

        
        $posts = DB::table('post as p')
                    ->select('p.*', 'p.id as post_db_id', 'p.contenuto as post_contenuto')
                    ->selectSub($votoSubquery, 'tipo_voto_utente')
                    ->where('p.reddit_id', $reddit_id)
                    ->first();

        if(!$posts) abort(404);

        $commenti = DB::table('commenti as c')
                        ->join('utenti as u', 'c.user_id', '=', 'u.id')
                        ->select('c.*', 'u.username', 'u.avatar as user_avatar')
                        ->where('c.post_id', $posts->post_db_id)
                        ->orderBy('c.data_commento', 'asc')
                        ->get();

        
        return view('/post_singolo', [
            'posts' => $posts,
            'commenti' => $commenti
        ]
        );
    }
}