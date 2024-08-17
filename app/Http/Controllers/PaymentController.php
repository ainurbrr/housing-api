<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Due;
use App\Models\House;
use Carbon\Carbon;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' =>'success get all payments',
            'payments'=>Payment::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'house_id' => 'required|exists:houses,id',
            'payment_date' => 'required',
            'type' => 'required|in:Kebersihan,Satpam',
            'month_quantity' => 'required|numeric|integer',
        ]);

        $house = House::findOrFail($data['house_id']);
        
        if ($data['resident_id']!=$house->residents_id){
            return response()->json([
                'message' => 'Error its not your house id'
            ], 401);
        }

        if ($data['type'] == 'Kebersihan') {
            $data['amount'] = 15000 * $data['month_quantity'];
        } else {
            $data['amount'] = 100000 * $data['month_quantity'];
        }

        $payments = Payment::create($data);

        $last_due = Due::where('house_id', $data['house_id'])->where('type', $data['type'])->orderBy('due_date', 'DESC')->first();

        $start_date = Carbon::now();
        if (!$last_due) {
            $start_date =  Carbon::now();
        } else {
            $start_date = Carbon::createFromFormat('Y-m-d', $last_due->due_date);
        }

        for ($x = 1; $x <= $data['month_quantity']; $x++) {
            Due::create([
                'resident_id' => $data['resident_id'],
                'house_id' => $data['house_id'],
                'payment_id' => $payments->id,
                'type' => $data['type'],
                'due_date' => !$last_due && $x == 1 ? $start_date : $start_date->addMonths()
            ]);
        }

        return response()->json([
            'message' => 'success create payment',
            'payments' => $payments
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $dues = Due::where('payment_id', $payment->id)->get();
        return response()->json([
            'payment' => $payment,
            'dues' => $dues,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'house_id' => 'required|exists:houses,id',
            'payment_date' => 'required',
            'type' => 'required|in:Kebersihan,Satpam',
            'month_quantity' => 'required|numeric|integer',
        ]);

        if ($data['resident_id']!=$payment->resident_id || $data['house_id']!=$payment->house_id){
            return response()->json([
                'message' => 'Error its not your house id'
            ], 401);
        }

        if ($data['type'] == 'Kebersihan') {
            $data['amount'] = 15000 * $data['month_quantity'];
        } else {
            $data['amount'] = 100000 * $data['month_quantity'];
        }

        $payment_month_quantity = Due::where('payment_id', $payment->id)->where('type', $data['type'])->count();
        if (!$payment_month_quantity) {
            $duesToDelete = Due::where('payment_id', $payment->id)
                ->orderBy('due_date', 'desc')
                ->get();

            foreach ($duesToDelete as $due) {
                $due->delete();
            }
        }


        $payment->update($data);

        $last_due = Due::where('house_id', $data['house_id'])->where('type', $data['type'])->orderBy('due_date', 'DESC')->first();

        $start_date = Carbon::now();
        if (!$last_due) {
            $start_date = Carbon::now();
        } else {
            $start_date = Carbon::createFromFormat('Y-m-d', $last_due->due_date);
        }

        if ($payment_month_quantity < $data['month_quantity']) {
            $dif = $data['month_quantity'] - $payment_month_quantity;
            for ($x = 0; $x < $dif; $x++) {
                Due::create([
                    'resident_id' => $data['resident_id'],
                    'house_id' => $data['house_id'],
                    'payment_id' => $payment->id,
                    'type' => $data['type'],
                    'due_date' => !$last_due && $x == 0 ? $start_date : $start_date->addMonths()
                ]);
            }
        } elseif ($payment_month_quantity > $data['month_quantity']) {

            $diff = $payment_month_quantity - $data['month_quantity'];
            // Ambil Due terakhir yang harus dihapus
            $duesToDelete = Due::where('payment_id', $payment->id)
                ->orderBy('due_date', 'desc')
                ->take($diff)
                ->get();

            foreach ($duesToDelete as $due) {
                $due->delete();
            }
        }

        return response()->json([
            'message' => 'success update payment',
            'payments' => $payment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $duesToDelete = Due::where('payment_id', $payment->id)->get();
        foreach ($duesToDelete as $due) {
            $due->delete();
        }
        $payment->delete();
        return response()->json(['message' => 'payment deleted successfully'], 200);
    }
}
