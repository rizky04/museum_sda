<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'category_id', 'description', 'cover_image', 'pdf_file'
    ];

    // Relasi balik ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Relasi ke komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
