<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'book_id',
        'name',
        'phone',
        'comment',
    ];

    /**
     * Relasi ke Book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
