<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        $data = User::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Ditemukan',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required',
            'is_active' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]);
        }

        try {
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dibuat',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
                'data' => $data
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
                'data' => $data
            ], 500);
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
        $data = User::find($id);

        if(!$data){
            return response()->json([
                'success' => true,
                'message' => 'Data Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Ditemukan',
            'data' => $data
        ]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required',
            'is_active' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ]);
        }

        try {
            $user = User::find($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'is_active' => $request->is_active
            ]);

            $data = User::find($id);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Update',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
                'data' => $data
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
                'data' => $data
            ], 500);
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
        try {
            $user = User::find($id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Hapus',
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
                'data' => $data
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => true,
                'message' => "Error on line {$e->getLine()}: {$e->getMessage()}",
                'data' => $data
            ], 500);
        }
    }
}
