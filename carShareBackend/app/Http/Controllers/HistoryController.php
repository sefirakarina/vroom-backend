<?php

namespace App\Http\Controllers;

use App\Booking;
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

    // 40. As an admin, I want to see all  customer's booking histories
    public function index()
    {
        $history = History::join('customers','histories.customer_id','=','customers.id')
            ->join('cars','histories.car_id','=','cars.id')
            ->join('users','histories.customer_id','=','users.id')
            ->join('credit_cards','credit_cards.customer_id','=','histories.customer_id')
            ->join('locations','histories.return_location_id','=','locations.id')
            ->select('histories.*', 'users.name', 'users.email','customers.id as customer_id', 'customers.address','customers.phone_number',
                'customers.license_number','customers.status as customer_status', 'credit_cards.id as cc_id', 'credit_cards.name as cc_name',
                'credit_cards.number', 'credit_cards.exp_date','cars.location_id as car_location_id', 'cars.plate', 'cars.type',
                'cars.capacity', 'cars.image_path', 'cars.availability', 'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->get();

        $array = Array();
        $array['data'] = $history;

        if(count($history) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'history not found'], 404);
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
        try{

            $booking = Booking::where('id', $request->booking_id)
                ->select('bookings.*')
                ->first();

            $bookingToBeDeleted= Booking::find($request->booking_id);

            if ($bookingToBeDeleted != null){

                $bookingToBeDeleted->delete();

                $history = History::create ([
                    'customer_id' => $booking-> customer_id,
                    'car_id' => $booking-> car_id,
                    'return_location_id' => $booking-> return_location_id,
                    'begin_time' => $booking-> begin_time,
                    'return_time' => $request->return_time
                ]);

            }else{
                return response()->json(['error' => 'Unable to delete the booking and create history'], 404);
            }
            return response()->json(['message' => $history], 200);

        }catch(\Exception $e) {
            return response()->json(['error' => $e], 404);
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
