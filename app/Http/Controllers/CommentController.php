<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Mengambil daftar komentar berdasarkan ID Buku
     */
    public function index($bookId)
    {
        $comments = Comment::where('book_id', $bookId)
                            ->latest()
                            ->get();

        return response()->json($comments);
    }

    /**
     * Menyimpan komentar baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create([
            'book_id' => $request->book_id,
            'name'    => strip_tags($request->name), // Bersihkan tag HTML
            'phone'   => strip_tags($request->phone),
            'comment' => strip_tags($request->comment),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Komentar berhasil dikirim!',
            'data'    => $comment
        ], 201);
    }
}
