<?php

namespace App\Adapters\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string',
            'book_ids' => 'required|array|min:1|max:3',
        ]);

        $loanId = (string) Str::uuid();
        $userId = $request->input('auth_user_id');

        DB::transaction(function () use ($request, $loanId, $userId) {
            DB::table('library.loan')->insert([
                'id' => $loanId,
                'member_id' => $request->input('member_id'),
                'loaned_by_user_id' => $userId,
                'loaned_at' => Carbon::now(),
            ]);

            foreach ($request->input('book_ids') as $bookId) {
                DB::table('library.loan_item')->insert([
                    'id' => (string) Str::uuid(),
                    'loan_id' => $loanId,
                    'book_id' => $bookId,
                    'due_at' => Carbon::now()->addDays(14),
                ]);

                DB::table('library.book')->where('id', $bookId)->decrement('available_copies');
            }
        });

        return response()->json(['message' => 'Préstamo registrado exitosamente', 'id' => $loanId], 201);
    }
}