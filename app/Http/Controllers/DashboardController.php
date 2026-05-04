<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. WIDGET ANGKA (TOP CARDS)
        $totalItems = Item::count();
        
        // Ambil waktu persis saat ini secara dinamis (tanpa dikunci tahunnya)
        $today = Carbon::now();
        $currentMonth = $today->month;
        $currentYear = $today->year;

        // Menghitung frekuensi transaksi bulan ini
        $inboundThisMonth = Transaction::where('type', 'in')
            ->whereMonth('tanggal_fisik', $currentMonth)
            ->whereYear('tanggal_fisik', $currentYear)
            ->count();

        $outboundThisMonth = Transaction::where('type', 'out')
            ->whereMonth('tanggal_fisik', $currentMonth)
            ->whereYear('tanggal_fisik', $currentYear)
            ->count();

        // 2. AKTIVITAS TERKINI (5 Transaksi Terakhir)
        // Menarik data transaksi lengkap dengan relasi nama barangnya
        $recentTransactions = Transaction::with('item')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 3. DATA UNTUK GRAFIK (7 Hari Terakhir dari hari ini)
        $chartLabels = [];
        $chartDataIn = [];
        $chartDataOut = [];

        // Looping mundur dari 6 hari yang lalu sampai hari ini (total 7 hari)
        for ($i = 6; $i >= 0; $i--) {
            // Gunakan clone agar variabel $today tidak berubah nilainya saat di-subDays
            $date = (clone $today)->subDays($i);
            
            // Format untuk sumbu X di grafik (Misal: 30 Apr)
            $chartLabels[] = $date->format('d M'); 
            $dateString = $date->format('Y-m-d');

            // Hitung jumlah transaksi In & Out pada tanggal tersebut
            $chartDataIn[] = Transaction::where('type', 'in')->whereDate('tanggal_fisik', $dateString)->count();
            $chartDataOut[] = Transaction::where('type', 'out')->whereDate('tanggal_fisik', $dateString)->count();
        }

        // Kirim semua data ke halaman view
        return view('dashboard', compact(
            'totalItems', 'inboundThisMonth', 'outboundThisMonth', 
            'recentTransactions', 'chartLabels', 'chartDataIn', 'chartDataOut'
        ));
    }
}