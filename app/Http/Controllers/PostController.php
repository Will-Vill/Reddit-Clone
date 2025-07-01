<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function recuperaPostSingolo($reddit_id){
        if(!session('id')){
            error_log("Accesso non autorizzato al post singolo: utente non autenticato.");
            return redirect('/login');
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

    private function scaricaImmagine($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; RedditEsempio/1.0)');
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpcode === 200) ? $data : false;
    }

    public function postVoto(Request $request){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => 'Utente non autenticato'], 401);
        }

        $user_id = session('id');
        $reddit_id = $request->input('reddit_id');
        $titolo = $request->input('titolo');
        $autore = $request->input('autore');
        $subreddit = $request->input('subreddit');
        $url = $request->input('url');
        $thumbnail = $request->input('thumbnail');
        $contenuto = $request->input('contenuto');
        $tipo_voto_utente = $request->input('tipo_voto_utente');
        $voto_attuale_post = $request->input('voto_attuale_post');

        $post_esistente = DB::selectOne('SELECT id, voto FROM post WHERE reddit_id = ?', [$reddit_id]);

        $immagine_path = null;

        if($post_esistente){
            $post_id = $post_esistente->id;
            $voto_attuale_post = $post_esistente->voto;
            $flag_salvataggio_post = "Il post esiste";
        } else {
            if ($url && preg_match('/\.(jpeg|jpg|png)$/i', $url)){
                $image_data = $this->scaricaImmagine($url);
                if($image_data == false){
                    error_log("Impossibile scaricare l'immagine da: $url");
                } else {
                    $estensione = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $nome_file = 'img_' . uniqid() . '.' . $estensione;

                    $img = imagecreatefromstring($image_data); // crea una risorsa immagine

                    if($img != false){
                        $salva_path = tempnam(sys_get_temp_dir(), 'upload');

                        if (in_array($estensione, ['jpeg', 'jpg'])){
                            imagejpeg($img, $salva_path, 75); // salva l'immagine jpg sul disco comprimendola del 75 %
                        } elseif ($estensione === 'png'){
                            imagepng($img, $salva_path, 6); // salva l'immagine png sul disco livello di compressione 6 per png
                        } else {
                            error_log("Formato immagine non supportato: $estensione");
                        }
                        imagedestroy($img);

                        $percorsoStorage = 'uploads/' . $nome_file;
                        Storage::disk('public')->put($percorsoStorage, file_get_contents($salva_path));
                        unlink($salva_path);

                        $immagine_path = 'storage/' . $percorsoStorage;
                    } else {
                        error_log("Impossibile creare immagine da questo url: $url");
                    }
                }
            }
            $tipo_contenuto = $immagine_path ? 'image' : ($url ? 'link' : 'text');

            $inserisci_query = DB::insert('INSERT INTO post (user_id, reddit_id, subreddit, titolo, autore, contenuto, tipo_contenuto, url, thumbnail, voto, immagine_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
            [null, $reddit_id, $subreddit, $titolo, $autore, $contenuto, $tipo_contenuto, $url, $thumbnail, $voto_attuale_post, $immagine_path]);

            if($inserisci_query){
                $post_id = DB::getPdo()->lastInsertId();
                $flag_salvataggio_post = "Post salvato con successo nel database";
            } else {
                return response()->json(['success' => false, 'error' => "'Errore nel salvataggio del post"]);
            }
        }

        $voto_esistente = DB::selectOne('SELECT tipo_voto FROM voti_utenti WHERE user_id = ? AND post_id = ?', [$user_id, $post_id]);
        $tipo_voto_utente_precedente = $voto_esistente ? intval($voto_esistente->tipo_voto) : 0;


        $tipo_differenza_voto = $tipo_voto_utente - $tipo_voto_utente_precedente;
        $nuovo_voto_post = $voto_attuale_post + $tipo_differenza_voto;

        if($tipo_voto_utente == 0){
            DB::delete('DELETE FROM voti_utenti WHERE user_id = ? AND post_id = ?', [$user_id, $post_id]);
        } else if($voto_esistente){
            DB::update('UPDATE voti_utenti SET tipo_voto = ? WHERE user_id = ? AND post_id = ?',[$tipo_voto_utente, $user_id, $post_id]);
        } else {
            DB::insert('INSERT INTO voti_utenti (user_id, post_id, tipo_voto) VALUES (?, ?, ?)', [$user_id, $post_id, $tipo_voto_utente]);
        }

        DB::update('UPDATE post SET voto = ? WHERE id = ?',[$nuovo_voto_post, $post_id]);

        return response()->json(['success' => true, 'nuovo_voto_post' => $nuovo_voto_post, 'flag_salvataggio_post' => $flag_salvataggio_post, 'nuovo_tipo_voto_utente' => $tipo_voto_utente]);
    }



    private function recuperaPostQuery($user_id) {

        $votoSubquery = DB::table("voti_utenti as v")
                            ->select('v.tipo_voto')
                            ->where('v.user_id', $user_id)
                            ->whereColumn('v.post_id', 'p.id')
                            ->limit(1);

        return DB::table("post as p")
            ->select(
                'p.id as post_db_id', 'p.reddit_id', 'p.subreddit', 'p.titolo', 'p.autore',
                'p.contenuto as post_contenuto', 'p.immagine_path', 'p.url', 'p.thumbnail', 'p.voto',
                'p.tipo_contenuto', 'p.data_salvataggio'
            )
            ->selectSub($votoSubquery, 'tipo_voto_utente');
    }



    public function postInizialiDB(){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => "Utente non autenticato"], 401);
        }

        $user_id = session('id');

        $posts = $this->recuperaPostQuery($user_id)
                    ->orderBy('p.voto', 'desc')
                    ->orderBy('p.data_salvataggio', 'desc')
                    ->limit(30)
                    ->get();

        
        
        return response()->json(['success' => true, 'posts' => $posts]);
    }



    public function postUtenti(){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => "Utente non autenticato"], 401);
        }

        $user_id = session('id');

        $id_post_commentati = DB::table('commenti')
                                ->select('post_id')
                                ->where('user_id', $user_id);

        
        $id_post_votati = DB::table('voti_utenti')
                            ->select('post_id')
                            ->where('user_id', $user_id);

        
        $posts = $this->recuperaPostQuery($user_id)
                    ->where('p.user_id', $user_id)
                    ->orWhereIn('p.id', $id_post_commentati) // controlla se il valore di p.id è presente nella lista degli id_post_commentati
                    ->orWhereIn('p.id', $id_post_votati) // controlla se il valore di p.id è presente nella lista degli id_post_votati
                    ->distinct()
                    ->orderBy('p.data_salvataggio', 'desc')
                    ->orderBy('p.id', 'desc')
                    ->get();
        
        return response()->json(['success' => true, 'data' => $posts]);
                    
    }




    public function postCommenti(Request $request){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => "Utente non autenticato"], 401);
        }

        $user_id = session('id');

        $contenuto = $request->input('commento');
        $post_id = $request->input('post_id');

        $inserisci_commento = DB::insert('INSERT INTO commenti (post_id, user_id, contenuto) VALUES (?, ?, ?)',
                                            [$post_id, $user_id, $contenuto]);


        if($inserisci_commento){
            $user_avatar = DB::table('utenti')
                        ->where('id', $user_id)
                        ->value('avatar');

            $username = DB::table('utenti')
                        ->where('id', $user_id)
                        ->value('username');

            $data_commento = now()->format('d/m/Y H:i');
        } else {
            return response()->json(['success' => false, 'error' => "Errore nell'inserimento del commento"], 500);
        }

        return response()->json(['success' => $inserisci_commento, 'username' => $username, 'user_avatar' => $user_avatar, 'data_commento' => $data_commento]);
    }


    public function postRecenti(Request $request){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => "Utente non autenticato"], 401);
        }

        $posts = DB::table('post')
                    ->select('reddit_id', 'titolo', 'subreddit')
                    ->orderBy('data_salvataggio', 'desc')
                    ->limit(5)
                    ->get();

        if($posts->isEmpty()) {
            return response()->json(['success' => false, 'error' => "Nessun post recente trovato"], 404);
        }

        return response()->json(['success' => true, 'post' => $posts]);
    }

    public function creaPostPagina(){
        if(!session('id')){
            return redirect()->route('login');
        }

        return view('crea_post');
    }

    /*public function creaPost(Request $request){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => "Utente non autenticato"], 401);
        }

        $user_id = session('id');

        $request->validate([
            "titolo" => ["required", "min:5" ,"max:100"],





        ])
    }*/


}