<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mapnav;

class MapnavController extends Controller
{
    private $Mapnav;

    public function __construct()
    {
        $this->Mapnav = new Mapnav();
    }

    public function index()
    {
        $data = [
            'title'     => 'WebGIS SI-ALDO',
            'kecamatan' => $this->Mapnav->dataKecamatan(),
        ];

        return view('components.mapnav', $data);
    }
}
