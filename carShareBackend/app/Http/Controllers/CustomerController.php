<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use App\User;
use App\Booking;
use App\Location;
use App\Car;

class CustomerController extends Controller
{

    protected $customer;
    public function __construct(Customer $customer){
        //$this->middleware('auth:api', ['except' => ['index']]);
        $this->customer = $customer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                $user = User::create ([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role
                ])->customers()->create ( [
                    //'user_id' => $request->user_id,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'license_number' => $request->license_number,
                    'status' => false
                ])->creditCards()->create( [
                    'name' => $request->cc_name,
                    'number' => $request->number,
                    'exp_date' => $request->exp_date
                ]);
                return response()->json(['message' => 'successfully create user'], 200);
            }catch (\Exception $e){
                return response()->json(['error' => 'Username duplication'], 422);
            }

        } else {
            return response()->json(['error' => 'Failed to add user'], 404);
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

//    public function showCustomerBooking($id){
//        $customer = Booking::join('cars','bookings.car_id','=','cars.id')
//            ->join('locations','bookings.return_location_id','=','locations.id')
//            ->join('customers','bookings.customer_id','=','customers.id')
//            ->select('bookings.*', 'cars.*','locations.*', 'customers.*')
//            ->where('bookings.id', '=', $id)
//            ->get();
//        $array = Array();
//        $array['data'] = $booking;
//        if(count($booking) > 0)
//            return response()->json($array, 200);
//        return response()->json(['error' => 'booking not found'], 404);
//    }
}
