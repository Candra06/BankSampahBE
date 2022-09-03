<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanSampah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {
            $request->validate(
                [
                    'username' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'wilayah' => 'required',
                ]
            );

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'wilayah_id' => $request->wilayah,
                'password' => bcrypt($request->password),
                'point' => 0,
                'role' => 'User'
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            // return response()->json([
            //     'status_code' =>400,
            //     'message' => 'Success',
            //     'error' => $user
            // ]);
            return $th;
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            $user = User::where('username', $request->username)->first();
            if ($user) {
                if (password_verify($request->password, $user->password)) {
                    $tokenResult = $user->createToken('authToken')->plainTextToken;
                    return response()->json([
                        'status_code' => 200,
                        'access_token' => 'Bearer ' . $tokenResult,
                        'data' => $user
                    ]);
                } else {
                    return response()->json([
                        'status_code' => 401,
                        'data' => 'Password Salah',

                    ]);
                }
            } else {
                return response()->json([
                    'status_code' => 401,
                    'data' => 'Username tidak terdaftar',

                ]);
            }
        } catch (\Throwable $th) {
            return $th;
            //throw $th;
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $data = User::where('email', $request->email)->first();
            if ($data) {
                return response()->json(
                    [
                        'data' => 'Email terdaftar',
                        'id' => $data->id
                    ],
                    200
                );
            } else {
                return response()->json(['error' => 'Email tidak terdaftar'], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'error' => $th,
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            User::where('id', $request->id)->update(['password' => bcrypt($request->password)]);
            return response()->json(['data' => 'Berhasil memperbarui password'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'error' => $th,
            ]);
        }
    }

    public function updateProfil(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'required',
                    'email' => 'required',
                ]
            );

            $input['username'] = $request->username;
            $input['email'] = $request->email;

            if ($request->password) {
                $input['password'] = bcrypt($request->password);
            }
            $data = User::where('id', Auth::user()->id)->update($input);
            return $data;

            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'error' => $th,
            ]);
        }
    }

    public function addUser(Request $request)
    {

        try {
            $request->validate(
                [
                    'username' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'wilayah' => 'required',
                    'role' => 'required',
                ]
            );

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'wilayah_id' => $request->wilayah,
                'password' => bcrypt($request->password),
                'point' => 0,
                'role' => $request->role
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            // return response()->json([
            //     'status_code' =>400,
            //     'message' => 'Success',
            //     'error' => $user
            // ]);
            return $th;
        }
    }

    public function index()
    {
        try {
            $data = User::leftJoin('wilayah', 'wilayah.id', 'users.wilayah_id')
                ->select('users.*', 'wilayah.nomor_rw')
                ->where('users.wilayah_id', Auth::user()->wilayah_id)
                ->get();
            return response()->json([
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 400,
                'data' => $th
            ]);
        }
    }


    public function update(Request $request, $id)
    {


        try {
            $request->validate(
                [
                    'username' => 'required',
                    'email' => 'required',
                    'role' => 'required',
                ]
            );
            if ($request->password) {
                $update['password'] = bcrypt($request->password);
            }
            if ($request->wilayah) {
                $update['wilayah_id'] = $request->wilayah;
            }
            $update = ([
                'username' => $request->username,
                'email' => $request->email,

                'role' => $request->role
            ]);

            User::where('id', $id)->update($update);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function dashboard()
    {
        try {
            $data = [];
            $terbaik = [];
            $tmpterbaik = PengumpulanSampah::leftJoin('users', 'users.id', 'pengumpulan_sampah.user_id')
                ->select('users.username', DB::raw('SUM(pengumpulan_sampah.jumlah) as jumlah'))
                ->groupBy('users.id')
                ->orderBy('jumlah', 'DESC')
                ->limit(3)->get();
                foreach ($tmpterbaik as $dt) {
                    $tmp['username'] = $dt['username'];
                    $tmp['jumlah'] = number_format((float)$dt['jumlah'] / 1000, 2 , '.','');
                    array_push($terbaik, $tmp);
                }

            $data['terbaik'] = $terbaik;
            if (Auth::user()->role == 'User') {
                $poin = Auth::user()->point;
                $pengumpulan = PengumpulanSampah::where('user_id', Auth::user()->id)
                    ->sum('jumlah');
                $harian = PengumpulanSampah::where('user_id', Auth::user()->id)
                    ->whereDate('created_at', Carbon::now())
                    ->sum('jumlah');
                $mingguan = PengumpulanSampah::where('user_id', Auth::user()->id)
                    ->whereBetween('created_at', [
                        Carbon::now()->subDays(7),
                        Carbon::now()
                    ])
                    ->sum('jumlah');

                $bulanan = PengumpulanSampah::where('user_id', Auth::user()->id)
                    ->whereMonth('created_at', Carbon::now())
                    ->sum('jumlah');
                $kontribusi = ([
                    'harian' => $harian / 1000,
                    'mingguan' => $mingguan / 1000,
                    'bulanan' => $bulanan / 1000,
                ]);
                $rw = PengumpulanSampah::leftJoin('users', 'users.id', 'pengumpulan_sampah.user_id')
                    ->where('users.wilayah_id', Auth::user()->wilayah_id)
                    ->whereDate('pengumpulan_sampah.created_at', Carbon::now())
                    ->sum('pengumpulan_sampah.jumlah');
                $all = PengumpulanSampah::whereDate('created_at', Carbon::now())
                    ->sum('jumlah');
                $wilayah = ([
                    'rw' => $rw / 1000,
                    'all' => $all / 1000,
                ]);
                $data['point'] = $poin;
                $data['pengumpulan'] = $pengumpulan;
                $data['kontribusi'] = $kontribusi;
                $data['wilayah'] = $wilayah;
            } else {
                $rw = PengumpulanSampah::leftJoin('users', 'users.id', 'pengumpulan_sampah.user_id')
                    ->where('users.wilayah_id', Auth::user()->wilayah_id)
                    ->whereDate('pengumpulan_sampah.created_at', Carbon::now())
                    ->sum('pengumpulan_sampah.jumlah');
                $all = PengumpulanSampah::whereDate('created_at', Carbon::now())
                    ->sum('jumlah');
                $wilayah = ([
                    'rw' => $rw / 1000,
                    'all' => $all / 1000,
                ]);
                $data['wilayah'] = $wilayah;
            }
            return response()->json([
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 403,
                'message' => $th
            ]);
        }
    }
}
