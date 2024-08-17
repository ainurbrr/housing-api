<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Payment;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' =>'success get all expenses',
            'expenses'=>Expenses::all(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'description'=>'required',
            'amount'=>'required|numeric|integer',
            'date_expenses' =>'required|date',
        ]);

        $balance = Payment::sum('amount') - Expenses::sum('amount');

        if ($balance > $data['amount']) {
            $expenses = Expenses::create($data);
        }else {
            return [ 'message' => 'Saldo Kurang'];
        }

        return response()->json([
            'message' => 'Success make expenses',
            'expenses' => $expenses
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Expenses::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expenses = Expenses::findOrFail($id);
        $data = $request->validate([
            'description'=>'required',
            'amount'=>'required|numeric|integer',
            'date_expenses' =>'required|date',
        ]);

        $balance = Payment::sum('amount') - Expenses::sum('amount');

        if ($balance > $data['amount']) {
            $expenses->update($data);
        }else {
            return [ 'message' => 'Saldo Kurang'];
        }

        return response()->json([
            'message' => 'Success make update expenses',
            'expenses' => $expenses
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expenses = Expenses::findOrFail($id);
        $expenses->delete();
        return response()->json(['message' => 'expenses deleted successfully'], 200);
    }
}
