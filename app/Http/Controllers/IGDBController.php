<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Game;

class IGDBController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $certPath;

    public function __construct()
    {
        $this->clientId = 'ce4o57qjjaf4ola6aijv2oyyxcimvm';
        $this->clientSecret = 'rucpw03xi9117y5gdthytqxxx7f68b';
        $this->certPath = storage_path('certs/cacert.pem');

        if (empty($this->clientId) || empty($this->clientSecret)) {
            Log::error('IGDB credentials not configured');
            abort(500, 'Server configuration error');
        }

        if (!file_exists($this->certPath)) {
            Log::error('SSL certificate not found at: ' . $this->certPath);
            abort(500, 'SSL certificate configuration error');
        }
    }

    public function getPopularGames(Request $request)
    {
        try {
            $limit = $request->input('limit', 5);
            $offset = $request->input('offset', 0);

            $accessToken = $this->getAccessToken();

            $query = "fields name,summary,first_release_date,cover.url,involved_companies.company.name,platforms.name,genres.name; where category=0 & first_release_date!=null & platforms!=null & involved_companies!=null & summary!=null & version_parent=null; sort popularity desc; limit {$limit}; offset {$offset};";

            $response = Http::withOptions([
                    'verify' => $this->certPath,
                    'timeout' => 30,
                ])
                ->withHeaders([
                    'Client-ID' => $this->clientId,
                    'Authorization' => 'Bearer ' . $accessToken,
                ])
                ->withBody($query, 'text/plain')
                ->post('https://api.igdb.com/v4/games');

            if ($response->failed()) {
                Log::error('IGDB API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                abort(502, 'Error communicating with IGDB API');
            }

            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error('Exception in getPopularGames', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Internal server error');
        }
    }

    public function searchGames(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $limit = $request->input('limit', 20);

            $accessToken = $this->getAccessToken();

            $query = "fields name,summary,first_release_date,cover.url,platforms.name,platforms.abbreviation; where category = 0 & name ~ \"{$search}\"* & first_release_date != null & platforms != null & version_parent = null & (category != 3 | category = null) & themes != (42); limit {$limit};";

            $response = Http::withOptions([
                    'verify' => $this->certPath,
                    'timeout' => 30,
                ])
                ->withHeaders([
                    'Client-ID' => $this->clientId,
                    'Authorization' => 'Bearer ' . $accessToken,
                ])
                ->withBody($query, 'text/plain')
                ->post('https://api.igdb.com/v4/games');

            if ($response->failed()) {
                Log::error('IGDB search request failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                abort(502, 'Error communicating with IGDB API');
            }

            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error('Exception in searchGames', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Internal server error');
        }
    }

    private function getAccessToken()
    {
        return Cache::remember('igdb_access_token', 3600, function() {
            $response = Http::withOptions([
                    'verify' => $this->certPath,
                    'timeout' => 15,
                ])
                ->asForm()
                ->post('https://id.twitch.tv/oauth2/token', [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'client_credentials',
                ]);

            $data = $response->json();

            if (!isset($data['access_token'])) {
                Log::error('Failed to get access token', [
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);
                throw new \Exception('Failed to obtain access token from Twitch');
            }

            return $data['access_token'];
        });
    }

// app/Http/Controllers/IGDBController.php

public function syncGame(Request $request)
{
    try {
        $igdbId = $request->input('igdb_id');
        
        // Primero verifica si el juego ya existe localmente
        $localGame = Game::where('igdb_id', $igdbId)->first();
        
        if ($localGame) {
            return response()->json([
                'success' => true,
                'game' => $localGame,
                'message' => 'Juego ya existente en la base de datos'
            ]);
        }

        // Si no existe, obtén los detalles completos de IGDB
        $accessToken = $this->getAccessToken();
        
        $query = "fields name,summary,first_release_date,cover.url,involved_companies.company.name,platforms.name,platforms.abbreviation,genres.name; 
                 where id = {$igdbId};";
        
        $response = Http::withOptions([
                'verify' => $this->certPath,
                'timeout' => 30,
            ])
            ->withHeaders([
                'Client-ID' => $this->clientId,
                'Authorization' => 'Bearer ' . $accessToken,
            ])
            ->withBody($query, 'text/plain')
            ->post('https://api.igdb.com/v4/games');

        if ($response->failed() || empty($response->json())) {
            throw new \Exception('No se pudo obtener el juego de IGDB');
        }

        $igdbGame = $response->json()[0];

        $imageUrl = isset($igdbGame['cover']) 
            ? 'https:' . str_replace('t_thumb', 't_cover_big', $igdbGame['cover']['url'])
            : null;
        // Transforma los datos de IGDB a tu formato local
        $gameData = [
            'igdb_id' => $igdbId,
            'name' => $igdbGame['name'],
            'img' => $imageUrl,

            'description' => $igdbGame['summary'] ?? null,
            'launch_date' => isset($igdbGame['first_release_date'])
                            ? date('Y-m-d', $igdbGame['first_release_date'])
                            : null,
            'publisher' => $igdbGame['involved_companies'][0]['company']['name'] ?? 'Desconocido',
            'available_platforms' => isset($igdbGame['platforms'])
                                  ? implode(' ', array_map(
                                      fn($p) => $p['abbreviation'] ?? $p['name'], 
                                      $igdbGame['platforms']
                                    ))
                                  : 'PC',
            'genres' => isset($igdbGame['genres'])
                       ? implode(', ', array_map(
                           fn($g) => $g['name'], 
                           $igdbGame['genres']
                         ))
                       : null
        ];

        // Crea el juego en tu base de datos local
        $game = Game::create($gameData);

        return response()->json([
            'success' => true,
            'game' => $game,
            'message' => 'Juego sincronizado correctamente'
        ]);

    } catch (\Exception $e) {
        Log::error('Error al sincronizar juego: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al sincronizar el juego: ' . $e->getMessage()
        ], 500);
    }
}

}