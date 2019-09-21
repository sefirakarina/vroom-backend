<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Booking;
use App\Location;
use App\Customer;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $booking;
    public function __construct(Booking $booking)
    {
        $this->middleware('auth:api');
        $this->booking = $booking;
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
        if($request !=null){

            try{

                $locationCapacity = Location::where('id', $request->return_location_id)
                   ->select('slot', 'current_car_num')
                   ->first();

                $currentAvailableSlot = $locationCapacity->slot - $locationCapacity->current_car_num;

                $returnedCarNum = Booking::where('return_time', '<=', $request->return_time)
                   -> where('return_location_id', '=', $request->return_location_id)
                   ->get();
                $returnedCarTotal = $returnedCarNum ->count();

                $toBeBookedCarNum = Booking::where('begin_time', '=>', $request->return_time)
                    -> where('return_location_id', '=', $request->return_location_id)
                    ->get();
                $toBeBookedCarTotal = $toBeBookedCarNum  ->count();

                $totalAvailableSlot = $currentAvailableSlot - $returnedCarTotal + $toBeBookedCarTotal;

                if($totalAvailableSlot > 0){

                    $book = Booking::create ([

                        'customer_id' => $request-> customer_id,
                        'car_id' => $request-> car_id,
                        'return_location_id' => $request-> return_location_id,
                        'begin_time' => $request-> begin_time,
                        'return_time' => $request-> return_time,
                        'status' => false
                    ]);

                    return response()->json(['message' => 'successfully create booking'], 200);
                }
                else
                    return response()->json(['error1' => 'the return location will be full on that day'], 404);
            }catch (\Exception $e){
                return response()->json(['error2' => $e], 404);
            }

        } else {
            return response()->json(['error3' => 'Failed to add booking'], 404);
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
        if($request !=null){

            try{

                $currentReturnLocation = Booking::where('id', $id)
                    ->select('return_location_id')
                    ->first();

                $totalAvailableSlot = 1;

                if($currentReturnLocation -> return_location_id != $request->return_location_id){

                    $locationCapacity = Location::where('id', $request->return_location_id)
                        ->select('slot', 'current_car_num')
                        ->first();

                    $currentAvailableSlot = $locationCapacity->slot - $locationCapacity->current_car_num;

                    $returnedCarNum = Booking::where('return_time', '<=', $request->return_time)
                        -> where('return_location_id', '=', $request->return_location_id)
                        ->get();
                    $returnedCarTotal = $returnedCarNum ->count();

                    $toBeBookedCarNum = Booking::where('begin_time', '=>', $request->return_time)
                        -> where('return_location_id', '=', $request->return_location_id)
                        ->get();
                    $toBeBookedCarTotal = $toBeBookedCarNum  ->count();

                    $totalAvailableSlot = $currentAvailableSlot - $returnedCarTotal + $toBeBookedCarTotal;
                }

                if($totalAvailableSlot > 0){

                    $book = Booking::where('id', $id)->update ([

                        'return_location_id' => $request-> return_location_id,
                        'begin_time' => $request-> begin_time,
                        'return_time' => $request-> return_time
                    ]);

                    return response()->json(['message' => 'successfully edit booking'], 200);
                }
                else
                    return response()->json(['error1' => 'the return location will be full on that day'], 404);
            }catch (\Exception $e){
                return response()->json(['error2' => $e], 404);
            }

        } else {
            return response()->json(['error3' => 'Failed to edit booking'], 404);
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
        $booking = Booking::find($id);
        if ($booking != null){
            $booking->delete();
            return response()->json(['message' => 'successfully delete the booking'], 200);
            }else{
             return response()->json(['error' => 'Unable to find the booking'], 404);
            }
    }

    public function showBookingByCusId($id){

        $booking= Booking::join('cars','bookings.car_id','=','cars.id')
            ->join('locations','bookings.return_location_id','=','locations.id')
            ->join('customers','bookings.customer_id','=','customers.id')
            ->select('customers.id as customer_id', 'bookings.*', 'cars.location_id as car_location_id','cars.plate','cars.type', 'cars.capacity', 'cars.image_path', 'cars.availability',
                        'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->where('customers.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $booking;
        if(count($booking) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'booking not found'], 404);

    }

    public function showBookingsByStatus($status)
    {
        $booking=Booking::join('cars','bookings.car_id','=','cars.id')
            ->join('locations','bookings.return_location_id','=','locations.id')
            ->join('customers','bookings.customer_id','=','customers.id')
            ->select('customers.id as customer_id', 'bookings.*', 'cars.location_id as car_location_id','cars.plate','cars.type', 'cars.capacity', 'cars.image_path', 'cars.availability',
                'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->where('bookings.status', '=', $status)
            ->get();
        $array = Array();
        $array['data'] = $booking;
        if(count($booking) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'no booking found'], 404);
    }

    public function showMyBookings()
    {
        $user = auth()->user();
        $customer = Customer::where('user_id','=', $user->id)->first();
        $booking=Booking::join('cars','bookings.car_id','=','cars.id')
        ->join('locations','bookings.return_location_id','=','locations.id')
        ->join('customers','bookings.customer_id','=','customers.id')
        ->select('customers.id as customer_id', 'bookings.*', 'cars.location_id as car_location_id','cars.plate','cars.type', 'cars.capacity', 'cars.image_path', 'cars.availability',
            'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
        ->where('bookings.customer_id', '=', $customer->id)
        ->where('bookings.status', '=', 0)
        ->get();
        $array = Array();
        $array['data'] = $booking;
        if(count($booking) > 0)
        return response()->json($array, 200);
        return response()->json(['error' => 'no booking found'], 404);

    }

}
