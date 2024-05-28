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

            $movie = Movies::whereId($movie_id)->first();

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
            'watchlist' => $watchlist
        ];
    }
}
