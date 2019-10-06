<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     *
     */

    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth:api');
        $this->user = $user;
    }

    public function index()
    {
        $user = User::where('role', 'admin')
                ->select('users.name', 'users.email', 'users.role')
                ->get();

        $array = Array();
        $array['data'] = $user;

        if ($user != null) {
            return response()->json($array, 200);
        } else {
            return response()->json(['error' => 'no admin found'], 404);
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
                $user = User::create ([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role
                ]);
                return response()->json(['message' => 'successfully create user'], 200);
            }catch (\Exception $e){
                return response()->json(['error' => 'Email duplication'], 422);
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
     *aaa
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

    // 24. As a super-admin, I want to be able to delete existing admin
    public function deleteAdmin($id)
    {
        $deleted = User::where('id', $id)
            ->where('role', 'admin')
            ->delete();

        if($deleted == 1)
            return response()->json(['message' => 'admin deleted'], 200);
        return response()->json(['error' => 'admin not found'], 404);
    }
}
