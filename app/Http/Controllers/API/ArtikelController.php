<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Artikel::all();
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
                'judul' => 'required',
                'konten' => 'required'
            ]);
            
            Artikel::create(['judul' => $request->judul,
            'konten' => $request->konten]);
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
                'judul' => 'required',
                'konten' => 'required'
            ]);

            Artikel::where('id', $id)->update(['judul' => $request->judul,
            'konten' => $request->konten]);
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
            $data = Artikel::where('id', $id)->first();
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
            Artikel::where('id', $id)->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
