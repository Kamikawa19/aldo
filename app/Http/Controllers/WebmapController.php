<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Webmap;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WebmapController extends Controller
{
    protected $id;
    protected $url;
    private $Webmap;

    public function __construct(UrlGenerator $url)
    {
        $this->Webmap = new Webmap();
        $this->url = $url;
    }

    public function index()
    {

        $data = [
            'title'     => 'WebGIS SI-ALDO',
            'kecamatan' => $this->Webmap->dataKecamatan(),
            'kelurahan' => $this->Webmap->dataKelurahan(),
            'kategori'  => $this->Webmap->dataCategory(),
            'ipal'      => $this->Webmap->dataIpal(),
            'kotaku'    => $this->Webmap->dataKotaku(),
            'mckumum'   => $this->Webmap->dataMCKUmum(),
            'mckkomunal' => $this->Webmap->dataMCKKomunal(),
            'ipalkomunal' => $this->Webmap->dataIPALKomunal(),
            'mckplus'    => $this->Webmap->dataMCKPlus(),

        ];

        return view('v_webmap', $data);
    }

    public function kecamatan($id)
    {
        $kec = $this->Webmap->detailKecamatan($id);
        $fileName =  $this->url->to('/data/kotaku.geojson');
        $data = [
            'title'     => 'WebGIS ' . $kec->name,
            'kecamatan' => $this->Webmap->dataKecamatan(),
            'kec'       => $kec,
            'filename'  => $fileName,
            'kel'       => $this->Webmap->dataKelurahan(),
            'kelbykec'  => $this->Webmap->dataKelurahanByKec($id),
            'kategori'  => $this->Webmap->dataCategory(),
            'build'      => $this->Webmap->dataBuild($id),
            'ipal'      => $this->Webmap->dataIpalKec($id),
            'kotaku'    => $this->Webmap->dataKotaku(),
            'mckplus'   => $this->Webmap->dataMCKPlusKec($id),
            'mckumum'   => $this->Webmap->dataMCKUmumKec($id),
            'mckkomunal' => $this->Webmap->dataMCKKomunalKec($id),
            'ipalkomunal' => $this->Webmap->dataIPALKomunalKec($id),
            'datasanitasi' => $this->Webmap->dataSanitasi($id),
            'secure'    => $this->Webmap->dataAman($id),
            'kepadatan'    => $this->Webmap->dataDensity($id),

        ];

        return view('v_kecamatan', $data);
    }



    public function getDataAttribute($value)
    {
        return json_decode($value, true);
    }
}
