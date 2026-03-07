<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Favorite;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    private $apiKey;
    private $client;

    public function __construct()
    {
        $this->apiKey = env('OMDB_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://www.omdbapi.com/',
            'timeout'  => 10,
        ]);
    }

    public function index()
    {
        return view('index');
    }

    public function search(Request $request)
    {
        $request->validate([
            's'    => 'required|string|min:1|max:100',
            'page' => 'nullable|integer|min:1|max:100',
        ]);

        $search = $request->input('s');
        $page   = $request->input('page', 1);

        try {
            $response = $this->client->request('GET', '/', [
                'query' => [
                    'apikey' => $this->apiKey,
                    's'      => $search,
                    'page'   => $page,
                    'type'   => 'movie', // opsional: hanya tampilkan film (bukan series/episode)
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'Response' => 'False',
                'Error'    => 'Gagal menghubungi server OMDB: ' . $e->getMessage()
            ], 500);
        }
    }

    // ─── Detail satu film berdasarkan IMDb ID ──────────────────────────────────
    public function detail(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $id = $request->input('id');

        try {
            $response = $this->client->request('GET', '/', [
                'query' => [
                    'apikey' => $this->apiKey,
                    'i'      => $id,
                    'plot'   => 'short', // atau 'full'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'Response' => 'False',
                'Error'    => 'Gagal mengambil detail film.'
            ], 500);
        }
    }

    public function toggleFavorite(Request $request)
    {
        $userId = Auth::id();
        $imdbID = $request->imdbID;

        $exists = Favorite::where('user_id', $userId)->where('imdbID', $imdbID)->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Dihapus dari favorit']);
        }

        Favorite::create([
            'user_id' => $userId,
            'imdbID'  => $imdbID,
            'title'   => $request->title,
            'year'    => $request->year,
            'poster'  => $request->poster,
        ]);

        return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke favorit']);
    }

    public function listFavorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();
        return view('favorites', compact('favorites'));
    }
}