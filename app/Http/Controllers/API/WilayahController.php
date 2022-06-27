<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        try {
            $data = Wilayah::all();
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'message' => $th
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nomor_rw' => 'required',
                'status' => 'required'
            ]);

            Wilayah::create([
                'nomor_rw' => $request->nomor_rw,
                'status' => $request->status
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
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
        try {
            $request->validate([
                'nomor_rw' => 'required',
                'status' => 'required'
            ]);

            Wilayah::where('id', $id)->update([
                'nomor_rw' => $request->nomor_rw,
                'status' => $request->status
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function show($id)
    {
        try {
            $data = Wilayah::where('id', $id)->first();
            return response()->json([
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $th;
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
            Wilayah::where('id', $id)->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
