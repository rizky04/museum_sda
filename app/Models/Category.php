<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['category_name'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function parent()
{
    return $this->belongsTo(Category::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(Category::class, 'parent_id');
}

// Opsional: Untuk mendapatkan semua produk dari kategori ini DAN sub-kategorinya
public function allBooks()
{
    return $this->hasMany(Book::class);
}
}
