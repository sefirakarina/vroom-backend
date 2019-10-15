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
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->customer = $customer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer=Customer::join('users', 'customers.user_id', 'users.id')
            ->join('credit_cards', 'credit_cards.customer_id', 'customers.id')
            ->select('users.name', 'users.email', 'users.role', 'customers.*', 'credit_cards.*')
            //->where('carts.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $customer;
        if(count($customer) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'customer not found'], 404);
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
                return response()->json(['error' => 'Username, Licence, or Credit Card duplication'], 422);
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
        $customer=Customer::join('users', 'customers.user_id', 'users.id')
            ->join('credit_cards', 'credit_cards.customer_id', 'customers.id')
            ->select('customers.*','users.name', 'users.email', 'users.role', 'credit_cards.id as cc_id', 'credit_cards.name as cc_name',
                    'credit_cards.number', 'credit_cards.exp_date')
            ->where('customers.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $customer;
        if(count($customer) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'customer not found'], 404);
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
     * 28. As a customer, I want to be able to edit my profile information
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request != null){
            try{
                Customer::where('id', '=', $id)->update([
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'license_number' => $request->license_number,
                ]);
                return response()->json(['message' => 'successfully edit customer'], 200);

            } catch (\Exception $e){
                print $e;
            }
        }else {
            return response()->json(['error' => 'customer not updated'], 404);
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
    // $customer = Customer::find($id);
    // $customer->delete();
    // return redirect('/customers')->with('success', 'Account deleted!');
    }

    public function activateCustomer($id)
    {

        $customer = Customer::find($id);
        $customer->status = 1;
        $customer->save();

        if($customer->status == 1){
            return response()->json(['message' => 'Successfully activate user'], 200);
        } else {
            return response()->json(['error' => 'Activation error'], 404);
        }
    }

    public function showInactiveCustomers()
    {
        $customer=Customer::join('users', 'customers.user_id', 'users.id')
            ->join('credit_cards', 'credit_cards.customer_id', 'customers.id')
            ->select('users.name', 'users.email', 'users.role', 'customers.*', 'credit_cards.*')
            ->where('customers.status', '=', 0)
            ->get();
        $array = Array();
        $array['data'] = $customer;
        if(count($customer) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'customer not found'], 404);
    }

    public function showActiveCustomers()
    {
        $customer=Customer::join('users', 'customers.user_id', 'users.id')
            ->join('credit_cards', 'credit_cards.customer_id', 'customers.id')
            ->select('users.name', 'users.email', 'users.role', 'customers.*', 'credit_cards.*')
            ->where('customers.status', '=', 1)
            ->get();
        $array = Array();
        $array['data'] = $customer;
        if(count($customer) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'customer not found'], 404);
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
