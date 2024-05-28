<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchlistRelation extends Model
{
    use HasFactory;
    protected $table = 'user_watchlist_relation';
    protected $fillable = [
        'user_id',
        'movie_id',
        'watchlist_id'
    ];
}
