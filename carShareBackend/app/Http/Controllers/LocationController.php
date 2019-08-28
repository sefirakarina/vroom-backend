<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use mysql_xdevapi\Exception;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $location;
    public function __construct(Location $location){
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->location = $location;
    }

    public function index()
    {
        $location = Location::all();

        $array = Array();
        $array['data'] = $location;

        if ($location != null) {
            return response()->json($array, 200);
        } else {
            return response()->json(['error' => 'Location not found'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Location::find($id);
        $array = Array();
        $array['data'] = $location;
        if (sizeof($array) > 0) {
            return response()->json($array, 200);
        } else {
            return response()->json(['error' => 'Location not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dup = false;

        $input_coordinate = $request->coordinate;

        // Check for duplication
        $count = DB::table('locations')
                        ->where('locations.coordinate', $input_coordinate)
                        ->count();

        if ($count > 0) $dup = true;


        //  Update the location if there is no duplication and the field is not null
        try{
            if (!$dup){
                $new_location = Location::where('id', $id)->update([
                    'address' => $request->address,
                    'coordinate' => $request->coordinate,
                    'slot' => $request->slot,
                    'current_car_num' => $request->current_car_num,
                ]);
                if ($new_location != null) {
                    return response()->json($new_location, 200);
                } else {
                    return response()->json(['error' => 'Location not updated'], 404);
                }
            }
        } catch (\Exception $e){
            return response()->json(['error' => ' Failed to edit car'], 404);
        }

        // TODO: Check CarController

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
