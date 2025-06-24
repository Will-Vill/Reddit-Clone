<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

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

    public function infoProfiloUtente(){
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

    public function aggiornaProfilo(Request $request){
        if(!session('id')){
            return response()->json([
                'success' => false,
                'message' => 'Utente non autenticato. Accesso negato.'
            ]);
        }

        $user_id = session('id');
        $response = [
            'success' => false,
            'message' => 'Nessuna operazione richiesta o dati non validi.',
            'updated_fields' => [
                'bio' => false,
                'password' => false
            ],
            'new_bio_html' => null,
            'require_logout' => false
        ];

        $updateData = [];

        if($request->has('bio')){
            $bio_trim = trim($request->input('bio'));
            $updateData['bio'] = $bio_trim;
            $response['updated_fields']['bio'] = true;
            $response['new_bio_html'] = $bio_trim ? nl2br(htmlspecialchars($bio_trim)) : '<em>Nessuna biografia impostata.</em>';
        }

        if($request->filled('password')){
            $password = $request->input('password');
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]
            ]);
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
            $response['updated_fields']['password'] = true;
        }

        if(!empty($updateData)){
            $updated = DB::table('utenti')
                            ->where('id', $user_id)
                            ->update($updateData);
            
            if($updated){
                $response['success'] = true;
                $messages = [];
                if($response['updated_fields']['bio']) $messages[] = "Bio";
                if($response['updated_fields']['password']) $messages[] = "Password";
                if(count($messages) > 0){
                    $response['message'] = implode(" e ", $messages) . (count($messages) > 1 ? " aggiornate" : " aggiornata") . " con successo.";
                } else {
                    $response['message'] = "Profilo aggiornato con successo.";
                }
                if($response['updated_fields']['password']){
                    $response['message'] .= " Per motivi di sicurezza, è necessario effettuare nuovamente il login. Sarai reindirizzato alla pagina di login.";
                    $response['require_logout'] = true;
                    session()->flush();
                } 
            } else {
                $response['success'] = true;
                $response['message'] = "Nessuna modifica effettuata (valori già presenti).";
            }
        } else {
            $response['message'] = 'Nessun dato valido fornito per l\'aggiornamento o password non valida.';
        }
        return response()->json($response);
    }
}
