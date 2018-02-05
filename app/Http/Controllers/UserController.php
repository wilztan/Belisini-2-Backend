<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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
        /*validation for new registrant*/
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        /*if validation not qualified, then error/*
        if($validator->fails()){
            return response()->json(['message'=>'wrong']);
        }

        /*if password and confirm not match*/
        if($request->password != $request->password_confirmation){
            return response()->json(['message'=>'wrong']);   
        }

        /*store user*/
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        /*return response*/
        return $this->prepareResult(true,["accessToken"=>$user->createToken('belisini2')->accessToken],[],'user verified');


        /*Previouse Try Out*/
        // return response()->json([
        //     'message' => 'success'
        // ], 201);
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

    public function userInfo()
    {
        /* to get User Info such as name, email, etc*/
        return response()->json(request()->user());
    }

    public function accessToken(Request $request)
    {
        $validate = $this->validations($request,"login");
        if($validate["errors"]){
            return $this->prepareResult(false,[],$validate['errors'],"error while validating users");
        }
        $user = User::where("email",$request->email)->first();
        if($user){
            if(hash::check($request->password,$user->password)){
                return $this->prepareResult(true,["accessToken"=>$user->createToken('belisini2')->accessToken],[],'user verified');
            }else{
                return $this->prepareResult(false, [], ["password" => "Wrong password"],"Password not matched");  
           }
 
       }else{
 
           return $this->prepareResult(false, [], ["email" => "Unable to find user"],"User not found");
 
       }
    }

    public function validations($request,$type)
    {
        $errors=[];
        $error = false;
        if($type=="login"){
            $validator = Validator::make($request->all(),[
                'email'=>'required|email|max:255',
                'password'=>'required',
            ]);
            if($validator->fails()){
                $error = true;
                $errors = $validator->errors();
            }
        }
        return ["error" => $error,"errors"=>$errors];
    }

    public function prepareResult($status,$data,$error, $msg)
    {
        /* returning json respnse */
        return [
            'status'=>$status,
            'data'=>$data,
            'message'=>$msg,
            'errors'=>$error,
        ];
    }
}
