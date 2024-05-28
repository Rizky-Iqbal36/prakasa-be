<?php

namespace App\Http\Controllers;

require_once __DIR__ . '/../../Exceptions/CustomException.php';

use App\Exceptions\NotFound;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    private $DB;
    public function __construct()
    {
        $this->middleware('passport.auth');
        $this->DB = DB::connection('mysql');
    }

    public function list(Request $req)
    {
        return [
            'data' => Movies::all()
        ];
    }

    public function create(Request $req)
    {
        /** @var Authenticatable $user */
        $user = auth()->user();

        $body = $req->json()->all();
        $rules = [
            'title' => 'required|string',
            'studio' => 'required|string',
            'thumbnail' => 'required|string',
        ];
        $queries_validator = Validator::make($body, $rules);
        $this->validateReq($queries_validator);

        $movie = Movies::create(array_merge(
            $body,
            ['creator_id' => $user->id]
        ));
        return [
            'movie' => $movie
        ];
    }

    public function update(Request $req)
    {
        $body = $req->json()->all();
        $rules = [
            'movie_id' => 'required|integer',
            'title' => 'nullable|string',
            'studio' => 'nullable|string',
            'thumbnail' => 'nullable|string',
        ];
        $queries_validator = Validator::make($body, $rules);
        $this->validateReq($queries_validator);

        $movie_id = $body['movie_id'];
        $movie = Movies::find($movie_id);
        if (is_null($movie))
            throw new NotFound('Movie not found');

        unset($body['movie_id']);
        Movies::whereId($movie_id)->update($body);

        return [
            'message' => "Movie updated"
        ];
    }

    public function delete(Request $req, $movie_id)
    {
        $movie = Movies::find($movie_id);
        if (is_null($movie))
            throw new NotFound('Movie not found');

        Movies::whereId($movie_id)->delete();
        return [
            'message' => "Movie deleted"
        ];
    }
}
