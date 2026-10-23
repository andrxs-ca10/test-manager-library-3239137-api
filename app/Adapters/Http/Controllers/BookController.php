<?php

namespace App\Adapters\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = DB::table('library.book')->whereNull('deleted_at')->get();
        return response()->json(['data' => $books], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'isbn' => 'required|string',
            'category_id' => 'required|string',
            'total_copies' => 'required|integer|min:1',
        ]);

        $id = (string) Str::uuid();

        DB::table('library.book')->insert([
            'id' => $id,
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'isbn' => $request->input('isbn'),
            'category_id' => $request->input('category_id'),
            'total_copies' => $request->input('total_copies'),
            'available_copies' => $request->input('total_copies'),
        ]);

        return response()->json(['message' => 'Libro creado con éxito', 'id' => $id], 201);
    }
}