<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

     public function averageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function reviewsCount()
    {
        return $this->reviews()->count();
    }
}
