<?php
namespace App\Http\Controllers;

use App\Car;
use App\Location;
use Illuminate\Http\Request;
class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $car;
    public function __construct(Car $car)
    {

        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->car = $car;

//        $car = Car::all();

        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.*')
            //->where('carts.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $car;
        if(count($car) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'car not found'], 404);

    }

    public function index()
    {
//        $car = Car::all();
        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.*')
            //->where('carts.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $car;
        if(count($car) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'car not found'], 404);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

            $locationCapacity = Location::where('id', $request->location_id)
                ->select('slot', 'current_car_num')
                ->first();

            $availableSlot = $locationCapacity->slot - $locationCapacity->current_car_num;

            if($availableSlot > 0){


                try{
                    $car = Car::create ([
                        'type' => $request->type,
                        'location_id' => $request->location_id,
                        'plate' => $request->plate ,
                        'capacity' => $request->capacity,
                        'availability' => $request->availability,
                    ]);

                    $location = Location::find($request->location_id);
                    $location-> current_car_num = $location-> current_car_num + 1;
                    $location->save();

                    return response()->json(['message' => 'successfully create car'], 200);
                }catch (\Exception $e){
                    return response()->json(['error' => 'Failed to add car'], 404);
                }


            }else{
                return response()->json(['error' => 'location is full'], 404);
            }
        }else{
            return response()->json(['error' => 'request is empty'], 404);
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
        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.*')
            ->where('cars.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $car;
        if(count($car) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'car not found'], 404);
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
}