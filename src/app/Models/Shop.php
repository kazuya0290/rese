<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'image', 'area_id', 'genre_id', 'description'];

    // Areaとのリレーション
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Genreとのリレーション
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function users()
    {
    return $this->belongsToMany(User::class);
    }
    
    public function favoredByUsers()
    {
    return $this->belongsToMany(User::class, 'favorites');
    }
}
