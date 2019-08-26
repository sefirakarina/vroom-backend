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

        $this->middleware('auth:api', ['except' => ['index', 'show', 'getByAvailability']]);
        $this->car = $car;
    }

    public function index()
    {
        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.coordinate', 'locations.address', 'locations.slot', 'locations.current_car_num')
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
                        'image_path' => $request->image_path,
                        'availability' => $request->availability,
                    ]);

                    $location = Location::find($request->location_id);
                    $location-> current_car_num = $location-> current_car_num + 1;
                    $location->save();

                    $array = Array();
                    $array['data'] = $car;

                    return response()->json($array, 200);
                }catch (\Exception $e){
                    return response()->json(['error' => 'Failed to add car, plate number already exist'], 404);
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
            ->select('cars.*', 'locations.coordinate', 'locations.address', 'locations.slot', 'locations.current_car_num')
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
        try{

            $currentLocation = Car::where('id', $id)
                ->select('location_id')
                ->first();

            $currentLocationId = $currentLocation->location_id;

            $car = Car::where('id', $id)->update([
                'type' => $request->type,
                'location_id' => $request->location_id,
                'plate' => $request->plate ,
                'capacity' => $request->capacity,
                'image_path' => $request->image_path,
                'availability' => $request->availability
            ]);

            if ($car != null) {

                if($currentLocationId != $request-> location_id){

                    $locationOld = Location::find($currentLocationId);
                    $locationOld-> current_car_num = $locationOld ->current_car_num -1 ;
                    $locationOld->save();

                    $locationNew = Location::find($request-> location_id);
                    $locationNew-> current_car_num = $locationNew ->current_car_num +1 ;
                    $locationNew->save();
                }

                return response()->json(['message' => 'update success'], 200);
            } else {
                return response()->json(['error' => 'Car not updated'], 404);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 'Failed to edit car'], 404);
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
        try{
            $car = Car::where('id', $id)->delete();

            if ($car != null) {

                $locationCarNum = Location::where('id', $id)
                    ->select('current_car_num')
                    ->first();

                $location = Location::find($id);
                $location-> current_car_num = $locationCarNum->current_car_num -1 ;
                $location->save();

                return response()->json(['message' => 'Car successfully deleted'], 200);
            } else {
                return response()->json(['error' => 'Car cannot be deleted'], 404);
            }

        }catch (\Exception $e){
            return response()->json(['error' => 'Failed to delete car'], 404);
        }

    }

    public function getByAvailability($availability){

        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.coordinate', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->where('availability', $availability)
            ->get();

        $array = Array();
        $array['data'] = $car;

        if ($car != null) {

            return response()->json($array, 200);
        } else {
            return response()->json(['error' => 'no car with such availability'], 404);
        }
    }
}
