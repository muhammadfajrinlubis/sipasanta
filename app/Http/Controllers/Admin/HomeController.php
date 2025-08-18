<?php

namespace App\Http\Controllers\Admin;

use App\Models\Laundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Ambil tahun dari request atau gunakan tahun saat ini
        $selectedYear = $request->input('year', null);

        // Ambil daftar tahun unik dari tabel pengaduan
        $availableYears = DB::table('pengaduan')
            ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Rekap petugas hanya untuk petugas di tabel users
        $rekap_petugas = DB::table('users')
            ->select('users.id', 'users.name')
            ->where('level', '3') // Filter hanya petugas
            ->get()
            ->map(function ($user) {
                $totalSelesai = DB::table('pengaduan')
                    ->where('status', 'selesai')
                    ->whereRaw("FIND_IN_SET(?, id_petugas)", [$user->id])
                    ->count();

                $totalSedang = DB::table('pengaduan')
                    ->where('status', 'Dikerjakan Oleh Petugas')
                    ->whereRaw("FIND_IN_SET(?, id_petugas)", [$user->id])
                    ->count();

                return (object) [
                    'id' => $user->id,
                    'nama_petugas' => $user->name,
                    'total_selesai' => $totalSelesai,
                    'total_sedang' => $totalSedang,
                ];
            });

        // Jika tahun dipilih, tampilkan data hanya untuk tahun tersebut
        if ($selectedYear) {
            $pengaduan_per_bulan = DB::table('pengaduan')
                ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('count(*) as jumlah'))
                ->whereYear('created_at', $selectedYear)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->pluck('jumlah', 'bulan');

            $total_pengaduan = DB::table('pengaduan')
                ->whereYear('created_at', $selectedYear)
                ->count();

            $laundry = DB::table('laundry')->count();

            $pengaduan_by_status = DB::table('pengaduan')
                ->select('status', DB::raw('count(*) as jumlah'))
                ->whereYear('created_at', $selectedYear)
                ->groupBy('status')
                ->pluck('jumlah', 'status');
        } else {
            // Jika tidak ada tahun yang dipilih, tampilkan data untuk semua tahun
            $pengaduan_per_bulan = DB::table('pengaduan')
                ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('count(*) as jumlah'))
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->pluck('jumlah', 'bulan');

            $total_pengaduan = DB::table('pengaduan')->count();

            $laundry = DB::table('laundry')->count();

            $pengaduan_by_status = DB::table('pengaduan')
                ->select('status', DB::raw('count(*) as jumlah'))
                ->groupBy('status')
                ->pluck('jumlah', 'status');
        }
        $total_pengaduan_selesai = DB::table('pengaduan')->where('status', 'selesai')->count();
        $total_pengaduan_ditolak = DB::table('pengaduan')->where('status', 'ditolak')->count();

        $totalLaundry = Laundry::count();
        $totalLaundrypetugas = Laundry::where('keterangan', '!=', 0)->count();

        // Total pendapatan dari semua laundry (biaya)
        $totalPendapatan = Laundry::sum('biaya');

       return view('admin.dashboard.index', compact(
        'pengaduan_per_bulan',
        'laundry',
        'totalLaundry',
        'totalLaundrypetugas',
        'total_pengaduan',
        'selectedYear',
        'availableYears',
        'pengaduan_by_status',
        'rekap_petugas',
        'total_pengaduan_selesai',
        'total_pengaduan_ditolak',
        'totalPendapatan'
    ));
    }





    public function change()
    {
        return view('admin.dashboard.change');
    }

    public function change_password(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Kata sandi Anda saat ini tidak cocok dengan kata sandi yang Anda berikan. Silakan coba lagi.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","Kata Sandi Baru tidak boleh sama dengan kata sandi Anda saat ini. Silakan pilih kata sandi lain.");
        }

        DB::table('users')
                ->where('id', Auth::User()->id)
                ->update([
                'password' => bcrypt($request->get('new-password'))]);

        return redirect('/admin/change')->with("success","Ganti Password Berhasil !");
    }

    public function keluar()
    {
        Auth::logout();

        return redirect()->route('login')->with("error","Akun Anda Belum Diaktifkan !");
    }



}
