<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanSampah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumpulanSampahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $data = [];
            if (Auth()->user()->role == 'Admin') {
                $data = PengumpulanSampah::leftJoin('users', 'users.id', 'pengumpulan_sampah.user_id')
                    ->whereDate('pengumpulan_sampah.created_at', Carbon::today())
                    ->select('pengumpulan_sampah.*', 'users.id', 'users.username', 'users.role')
                    ->get();
            } else {
                $data = PengumpulanSampah::leftJoin('users', 'users.id', 'pengumpulan_sampah.user_id')
                ->select('pengumpulan_sampah.*', 'users.id', 'users.username', 'users.role')
                ->where('pengumpulan_sampah.user_id', Auth()->user()->id)
                ->get();
            }
            // return PengumpulanSampah::whereDate('created_at', Carbon::today())->get();
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editPoin(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'jumlah' => 'required'
            ]);
            
            $user = User::where('id', $request->user_id)->first();
            $newPoin = $user->point - $request->jumlah;
            
            User::where('id', $user->id)->update(['point' => $newPoin]);

            return response()->json([
                'status_code' => 200,
                'data' => 'Success'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'jumlah' => 'required'
            ]);

            $poin = $request->jumlah * 0.02;
            $user = User::where('id', $request->user_id)->first();
            $newPoin = $user->point + $poin;

            PengumpulanSampah::create([
                'user_id' => $request->user_id,
                'jumlah' => $request->jumlah,
                'point' => $poin,
            ]);

            User::where('id', $user->id)->update(['point' => $newPoin]);

            return response()->json([
                'status_code' => 200,
                'data' => 'Success'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PengumpulanSampah  $pengumpulanSampah
     * @return \Illuminate\Http\Response
     */
    public function show(PengumpulanSampah $pengumpulanSampah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PengumpulanSampah  $pengumpulanSampah
     * @return \Illuminate\Http\Response
     */
    public function edit(PengumpulanSampah $pengumpulanSampah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PengumpulanSampah  $pengumpulanSampah
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PengumpulanSampah $pengumpulanSampah)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PengumpulanSampah  $pengumpulanSampah
     * @return \Illuminate\Http\Response
     */
    public function destroy(PengumpulanSampah $pengumpulanSampah)
    {
        //
    }
}
