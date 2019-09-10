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
    if($request !=null){

        try{
            $location = Location::create ([
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'slot' => $request->slot,
                'current_car_num' => 0
            ]);
            return response()->json(['message' => 'successfully create location'], 200);
        }catch (\Exception $e){
            return response()->json(['error' => 'address is duplicated'], 422);
        }
    
    } else {
        return response()->json(['error' => 'Failed to add location'], 404);
    }
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
        if($request != null){
            try{

                Location::where('id', $id)->update([
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'slot' => $request->slot,
                    'current_car_num' => $request->current_car_num,
                ]);
                return response()->json(['message' => 'successfully edit car'], 200);

            } catch (\Exception $e){
                return response()->json(['error' => 'location duplication'], 404);
            }

        }else {
            return response()->json(['error' => 'Location not updated'], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $location = Location::find($id);
       $car_num = $location->current_car_num;
       if ($car_num == 0){
       $location->delete();
       return response()->json(['message' => 'successfully delete location'], 200);
        // return redirect('/locations')->with('success', 'Location deleted!');
       }else{
        return response()->json(['error' => 'Unable to remove the location'], 404);
       }    
}
}
