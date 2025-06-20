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
}
