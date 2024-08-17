<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Controller Method for Yearly Summary
    public function summary()
    {
        // Mengumpulkan data pemasukan per bulan
        $incomeData = Payment::selectRaw('SUM(amount) as total, MONTH(payment_date) as month')
            ->whereYear('payment_date', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        // Mengumpulkan data pengeluaran per bulan
        $expenseData = Expenses::selectRaw('SUM(amount) as total, MONTH(date_expenses) as month')
            ->whereYear('date_expenses', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month');

        // Menghitung saldo sisa per bulan
        $balanceData = [];
        for ($i = 1; $i <= 12; $i++) {
            $income = $incomeData[$i] ?? 0;
            $expense = $expenseData[$i] ?? 0;
            $balanceData[$i] = $income - $expense;
        }

        $total_balance = Payment::sum('amount') - Expenses::sum('amount');

        // Mengembalikan data sebagai JSON
        return response()->json([
            'message' => 'success get summary',
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
            'balanceData' => $balanceData,
            'totalBalance' => $total_balance
        ], 200);
    }

    public function monthlyDetail($month)
    {
        // Mengumpulkan data pemasukan untuk bulan tertentu
        $incomeDetails = Payment::whereYear('payment_date', date('Y'))
            ->whereMonth('payment_date', $month)
            ->get(['id', 'resident_id', 'house_id', 'amount', 'payment_date']);

        // Mengumpulkan data pengeluaran untuk bulan tertentu
        $expenseDetails = Expenses::whereYear('date_expenses', date('Y'))
            ->whereMonth('date_expenses', $month)
            ->get(['id', 'description', 'amount', 'date_expenses']);

        // Menghitung total pemasukan dan pengeluaran
        $totalIncome = $incomeDetails->sum('amount');
        $totalExpense = $expenseDetails->sum('amount');

        // Mengembalikan data sebagai JSON
        return response()->json([
            'message' => 'success get monthly details',
            'month' => $month,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'incomeDetails' => $incomeDetails,
            'expenseDetails' => $expenseDetails,
        ], 200);
    }
}
