<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\HouseHistory;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' =>'success get all houses',
            'houses'=>House::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'residents_id'=>'exists:residents,id',
            'address' =>'required|string',
            'status' =>'required|in:Dihuni,Tidak Dihuni',
        ]);

        $data['status'] = $request->residents_id ? 'Dihuni' : 'Tidak Dihuni';

        $house = House::create($data);

        return response()->json([
            'message' => 'success create house',
            'house' => $house,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        return response()->json([
            'message' => 'success get house',
            'house'=>$house->load(['resident','resident_history','payment_history'])
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, House $house)
    {
        
        $data = $request->validate([
            'residents_id'=>'exists:residents,id',
            'address' =>'required|string',
            'status' =>'in:Dihuni,Tidak Dihuni',
        ]);

        $data['status'] = $request->residents_id ? 'Dihuni' : 'Tidak Dihuni';


        if ($data['status'] == 'Tidak Dihuni') {
            $resident = Resident::findOrFail($house->residents_id);
            $resident->house_id = null;
            $resident->save();
            HouseHistory::create([
                'house_id'=>$house->id,
                'resident_id'=>$house->residents_id,
                'start_date'=>$house->updated_at ? $house->updated_at : $house->created_at,
                'end_date'=>Carbon::now(),
            ]);
        }
        $data['residents_id'] = $request->residents_id ?? null;

        $house->update($data);


        return response()->json([
            'message' => 'success update house',
            'house' => $house,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        $house->delete();
        return response()->json(['message' => 'house deleted successfully'], 200);
    }

    public function assignResident(Request $request, $id){

        $validatedData = $request->validate([
            'residents_id' => 'required|exists:residents,id',
        ]);

        $house = House::findOrFail($id);

        $resident = Resident::findOrFail($validatedData['residents_id']);
        $resident->house_id = $id;
        $resident->save();

        if ($resident->id) {
            HouseHistory::create([
                'house_id'=>$house->id,
                'resident_id'=>$house->residents_id,
                'start_date'=>$house->updated_at ? $house->updated_at : $house->created_at,
                'end_date'=>Carbon::now(),
            ]);
        }
        $house->residents_id = $resident->id;
        $house->status = "Dihuni";
        $house->save();

        return response()->json([
            'message' => 'Success assign resident',
            'house' => $house,
            'resident' => $resident
        ], 200);
    }
}
