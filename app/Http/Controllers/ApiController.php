<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function fetchRedditPost(Request $request){
        $client_id = env('REDDIT_CLIENT_ID');
        $client_secret = env('REDDIT_CLIENT_SECRET');

        $subreddit = $request->input('subreddit', 'gaming');

        $ch = curl_init('https://www.reddit.com/api/v1/access_token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
            'User-Agent: php:ProgettoTest:v1.0 (by /u/williamvil)'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['access_token'])) {
            $token = $data['access_token'];
            
            $url = "https://oauth.reddit.com/r/{$subreddit}/hot?limit=10";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$token}",
                'User-Agent: php:ProgettoTest:v1.0 (by /u/williamvil)'
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
        } else {
            return response()->json(['error' => 'Failed to get token'], 500);
        }
    }

    public function fetchGemini(Request $request){
        $gemini_key = env('GEMINI_KEY');

        $titolo = $request->input('titolo', 'Titolo test');

        $prompt = "Genera un commento breve e pertinente per un post di Reddit intitolato \"" . $titolo . 
          "\". Il commento deve essere informale, cordiale e non più lungo di 2 frasi.";

        $request_data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ];

        $ch = curl_init("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$gemini_key}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
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
            [$user_id, $reddit_id, $subreddit, $titolo, $autore, $contenuto, $tipo_contenuto, $url, $thumbnail, $voto_attuale_post, $immagine_path]);

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

        if($voto_esistente){
            DB::update('UPDATE voti_utenti SET tipo_voto = ? WHERE user_id = ? AND post_id = ?',[$tipo_voto_utente, $user_id, $post_id]);
        } else {
            DB::insert('INSERT INTO voti_utenti (user_id, post_id, tipo_voto) VALUES (?, ?, ?)', [$user_id, $post_id, $tipo_voto_utente]);
        }

        DB::update('UPDATE post SET voto = ? WHERE id = ?',[$nuovo_voto_post, $post_id]);

        return response()->json(['success' => true, 'nuovo_voto_post' => $nuovo_voto_post, 'flag_salvataggio_post' => $flag_salvataggio_post, 'nuovo_tipo_voto_utente' => $tipo_voto_utente]);
    }

    public function postInizialiDB(){
        if(!session('id')){
            return response()->json(['success' => false, 'error' => "Utente non autenticato"], 401);
        }

        $user_id = session('id');

        $votoSubquery = DB::table("voti_utenti as v")
                            ->select('v.tipo_voto')
                            ->where('v.user_id', $user_id)
                            ->whereColumn('v.post_id', 'p.id')
                            ->limit(1);
        
        
        $posts = DB::table("post as p")
                    ->select('p.id as post_db_id', 'p.reddit_id', 'p.subreddit', 'p.titolo', 'p.autore', 
                    'p.contenuto as post_contenuto', 'p.immagine_path', 'p.url', 'p.thumbnail', 'p.voto',
                    'p.tipo_contenuto', 'p.data_salvataggio'
                    )
                    ->selectSub($votoSubquery, 'tipo_voto_utente')
                    ->orderBy('p.voto', 'desc')
                    ->orderBy('p.data_salvataggio', 'desc')
                    ->limit(30)
                    ->get();

        
        
        return response()->json(['success' => true, 'posts' => $posts]);
    }
}
