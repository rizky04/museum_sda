<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

       // Ambil data buku beserta kategorinya
    $books = Book::with('category')->get();
    // Ambil semua kategori untuk filter
    $categories = Category::all();

    return view('home.index', compact('books', 'categories'));
    }
}
