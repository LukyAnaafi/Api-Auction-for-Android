<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserRes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        // request()-> untuk mengambil data GET/POST pada laravel
        $email = request()->email;
        $password = request()->password;
        //auth()->attempt() berguna mencari data user pada database berdasarkan email dan password
        if(auth()->attempt(['email' => $email,'password' => $password])){
            // User::where() memanggil model/memanggil data pada table users
            $user = User::where('email',request()->email);
            // first() mengambil 1 data yang muncul pertama kali
            $userGet = $user->first();
            // get() mengambil semua data yang ada pada table database
            $user = $user->get();
            // UserRes::collection() berguna untuk tampilan yang akan di response pada json
            $userCol = UserRes::collection($user);
            // membuat token untuk login ke beberapa api
            $token = Auth::user()->createToken('AppsToken')->plainTextToken;
            // megembalikan data dengan cara menampilkan data dengan menggunakan json
            return response()->json([
                'message' => 'Selamat datang '.$userGet->name,
                'data' => [
                    'user' => $userCol[0],
                    'token' => $token
                ],
            ],200);
        }else{
            return response()->json([
                'message' => "Email/Password salah silahkan coba lagi!",
            ],404);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $level = 'user';
        if(request()->level){
            $level = request()->level;
        }
        $password = Hash::make($request->password);
        if($level == 'user'){
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password'=> $password,
                'level' => $level,
                'telp' => $request->telp
            ];
        }else{
            $data = [
                'id_staff' => mt_rand(100000,999999),    
                'name' => $request->name,
                'email' => $request->email,
                'password'=> $password,
                'level' => $level,
                'telp' => $request->telp
            ];
        }

        $user = User::create($data);
    

        $token = $user->createToken('Apps')->plainTextToken;
        $userGet = User::all()->where('id',$user->id)->first();
        return response()->json([
            'message' => "Welcome ".$userGet->name,
            'data' => [
                'user' => $userGet,
                'token' => $token
            ]
        ], 200);
    }

    public function update(Request $request, $id){

        $user = User::where('id',request()->id);
        
        if($user){
            $user->update($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $user,
            'message' => 'edit profile berhasil'
        ], 200);
        }

        
    }
}
