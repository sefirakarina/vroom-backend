<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $history;
    public function __construct(History $history)
    {
        $this->middleware('auth:api');
        $this->history = $history;
    }

    public function index()
    {
        //
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
        //
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
        //
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

    public function showMyHistories($id)
    {
//        $user = auth()->user();
//        $customer = Customer::where('user_id','=', $user->id)->first();
        $history=History::join('cars','histories.car_id','=','cars.id')
        ->join('locations','histories.return_location_id','=','locations.id')
        ->join('customers','histories.customer_id','=','customers.id')
        ->select('customers.id as customer_id', 'histories.*', 'cars.location_id as car_location_id','cars.plate','cars.type', 'cars.capacity', 'cars.image_path', 'cars.availability',
            'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
        ->where('histories.customer_id', '=', $id)
        ->get();
        $array = Array();
        $array['data'] = $history;
        if(count($history) > 0)
        return response()->json($array, 200);
        return response()->json(['error' => 'no booking found'], 404);

    }

}
