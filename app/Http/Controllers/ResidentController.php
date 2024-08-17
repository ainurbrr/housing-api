<?php

namespace App\Http\Controllers;

use App\Models\Resident;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' =>'success get all residents',
            'residents'=>Resident::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' =>'required|string',
            'ktp' =>'required|file',
            'status' =>'required|in:Tetap,Kontrak',
            'phone_number' =>'required|numeric|min_digits:9',
            'married' =>'required|in:Menikah,Belum Menikah',
        ]);
        if ($request->file('ktp')) {
            $data['ktp'] = $request->file('ktp')->store('ktp');
        }

        $resident = Resident::create($data);
        return response()->json([
            'message' =>'success create resident',
            'resident' => $resident
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resident $resident)
    {
        return response()->json([
            'message'=> 'Success get resident',
            'resident'=>$resident
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resident $resident)
    {
        $data = $request->validate([
            'full_name' =>'string',
            'ktp' =>'file',
            'status' =>'in:Tetap,Kontrak',
            'phone_number' =>'numeric|digits:10',
            'married' =>'in:Menikah,Belum Menikah',
        ]);

        if ($request->file('ktp')) {
            if($request->oldktp){
                Storage::delete($request->oldktp);
            }
            $data['ktp'] = $request->file('ktp')->store('ktp');
        }
        $resident->update($data);

        return response()->json([
            'message' =>'success update resident',
            'resident' => $resident
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resident $resident)
    {
        $resident->delete();
        return response()->json(['message' => 'resident deleted successfully'], 200);
    }
}
