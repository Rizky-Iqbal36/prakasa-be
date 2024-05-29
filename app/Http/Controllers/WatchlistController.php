<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Exceptions/CustomException.php';

use App\Exceptions\NotFound;
use App\Models\Movies;
use App\Models\Watchlist;
use App\Models\WatchlistRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WatchlistController extends Controller
{
    private $DB;
    public function __construct()
    {
        $this->middleware('passport.auth');
        $this->DB = DB::connection('mysql');
    }

    public function list()
    {
        /** @var Authenticatable $user */
        $user = auth()->user();

        $watchlist = array();
        foreach (WatchlistRelation::where('user_id', $user->id)->get()->toArray() as $watchlist_relation) {
            $movie_id = $watchlist_relation['movie_id'];
            $watchlist_id = $watchlist_relation['watchlist_id'];

            $movie = Movies::whereId($movie_id)->select('id', 'title', 'thumbnail', 'studio')->first();

            $search_watchlist = $this->searchArrayOfObject($watchlist_id, 'id', $watchlist);
            if ($search_watchlist['data_found']) {
                $watchlist[$search_watchlist['index']]['movies'][] = $movie;
            } else {
                $watchlist_data = Watchlist::whereId($watchlist_id)->first();
                $watchlist[] = [
                    'id' => $watchlist_id,
                    'name' => $watchlist_data->watchlist_name,
                    'movies' => [$movie]
                ];
            }
        }

        return [
            'data' => $watchlist
        ];
    }

    public function create(Request $req)
    {
        /** @var Authenticatable $user */
        $user = auth()->user();
        $user_id = $user->id;

        $body = $req->json()->all();
        $rules = [
            'name' => 'required|string',
            'movies' => 'required|array|min:1',
            'movies.*' => 'required|integer'
        ];
        $queries_validator = Validator::make($body, $rules);
        $this->validateReq($queries_validator);

        $watchlist = Watchlist::create(['user_id' => $user_id, 'watchlist_name' => $body['name']]);
        foreach ($body['movies'] as $movie_id) {
            WatchlistRelation::create([
                'user_id' => $user_id,
                'movie_id' => $movie_id,
                'watchlist_id' => $watchlist->id
            ]);
        }

        return ['data' => ["watchlist_id" => $watchlist->id]];
    }

    public function update(Request $req)
    {
        /** @var Authenticatable $user */
        $user = auth()->user();
        $user_id = $user->id;

        $body = $req->json()->all();
        $rules = [
            'id' => 'required|integer',
            'name' => 'nullable|string',
            'add' => 'nullable|array',
            'add.*' => 'nullable|integer',
            'remove' => 'nullable|array',
            'remove.*' => 'nullable|integer',
        ];
        $queries_validator = Validator::make($body, $rules);
        $this->validateReq($queries_validator);

        $watchlist_id = $body['id'];
        $watchlist = Watchlist::where([
            ['id', '=', $watchlist_id],
            ['user_id', '=', $user_id]
        ])->first();

        if (is_null($watchlist))
            throw new NotFound("Watchlist not found");

        $add_movies = $body['add'] ?? [];
        foreach ($add_movies as $add_movie) {
            WatchlistRelation::updateOrInsert([
                'user_id' => $user_id,
                'movie_id' => $add_movie,
                'watchlist_id' => $watchlist->id
            ]);
        }
        $remove_movies = $body['remove'] ?? [];
        if (count($remove_movies) > 0)
            WatchlistRelation::where([
                ['watchlist_id', '=', $watchlist_id],
                ['user_id', '=', $user_id]
            ])->whereIn('movie_id', $remove_movies)->delete();

        $name = @$body['name'];
        if (!is_null($name) && !empty($name))
            Watchlist::where([
                ['id', '=', $watchlist_id],
                ['user_id', '=', $user_id]
            ])->update(['watchlist_name' => $name]);

        return ['message' => "Operation successful"];
    }

    public function delete(Request $req, $watchlist_id)
    {
        /** @var Authenticatable $user */
        $user = auth()->user();
        $user_id = $user->id;

        $where_conditions = [
            ['id', '=', $watchlist_id],
            ['user_id', '=', $user_id]
        ];
        $watchlist = Watchlist::where($where_conditions)->first();
        if (is_null($watchlist))
            throw new NotFound('Watchlist not found');

        Watchlist::where($where_conditions)->delete();

        return ['message' => "Operation successful"];
    }
}
