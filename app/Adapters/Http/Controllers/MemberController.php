<?php

namespace App\Adapters\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index()
    {
        $members = DB::table('library.member')->get();
        return response()->json(['data' => $members], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'document_number' => 'required|string',
        ]);

        $id = (string) Str::uuid();

        DB::table('library.member')->insert([
            'id' => $id,
            'full_name' => $request->input('full_name'),
            'document_number' => $request->input('document_number'),
        ]);

        return response()->json(['message' => 'Socio creado con éxito', 'id' => $id], 201);
    }
}