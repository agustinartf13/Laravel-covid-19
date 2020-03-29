<?php

namespace App\Http\Controllers;

use App\Charts\CovidChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CovidController extends Controller
{
    public function chart() {
        $suspects = collect( Http::get('https://api.kawalcorona.com/indonesia/provinsi')
        ->json());

        //abil data yang di perluka saja [key]
        $suspectData = $suspects->flatten(1);

        // ambil value berdasarkan key = puluck->provinsi
        $labels = $suspectData->pluck('Provinsi');
        $data = $suspectData->pluck('Kasus_Posi');

        $colors = $labels->map(function($item) {
           return '#' . substr(md5(mt_rand()), 0, 6);
        });

        $chart = new CovidChart;
        $chart->labels($labels);
        $chart->dataset('Data Kasus Positif di Indonesia', 'pie', $data)->backgroundColor($colors);

        return view('corona', [
            'chart' => $chart
        ]);
    }
}