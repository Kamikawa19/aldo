<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\BuildGallery;
use App\Models\ContentPage;
use App\Models\Infographic;
use App\Models\Ipal;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FrontendController extends Controller
{
    private $Infographic;
    private $ContentPage;
    private $Ipal;

    public function __construct()
    {
        $this->Infographic = new Infographic();
        $this->ContentPage = new ContentPage();
        $this->Ipal = new Ipal();
    }

    public function index(Request $request)
    {
        $gallery = Build::with(['galleries'])->latest()->get();
        $recommendations = BuildGallery::with(['builds'])->inRandomOrder()->limit(5)->get();
        return view('v_landingpage', compact('gallery', 'recommendations'));
    }

    public function details(Request $request, $id)
    {
        $gallery = Build::with(['kecamatans', 'kelurahans', 'categories', 'galleries'])->where('id', $id)->firstOrFail();

        $other = Build::with(['galleries'])->inRandomOrder()->limit(5)->get()->except($gallery->id);
        return view('components.frontend.details', compact('gallery', 'other'));
    }

    public function detailipal(Request $request, $id)
    {
        $data = [
            'title'     => 'Detail IPALD',
            'ipal'  => $this->Ipal->ipalgallery($id),
            'gallery' => Ipal::with(['kelurahans', 'services', 'categories'])->where('id', $id)->firstOrFail()
        ];

        return view('components.frontend.detipal', $data);
    }

    public function info()
    {
        $data = [
            'title'     => 'Infografis',
            'info'      => $this->Infographic->cardInfo(),
        ];

        return view('v_info', $data);
    }

    public function download()
    {

        $data = [
            'title'     => 'Download Peraturan',
            'download'  => $this->ContentPage->download(),
        ];

        return view('v_download', $data);
    }
}
