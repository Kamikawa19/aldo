<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Build extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;


    public const STATUS_RADIO = [
        'Aktif'           => 'Aktif',
        'Non-aktif'       => 'Non-Aktif',
        'Perlu Perbaikan' => 'Perlu Perbaikan',
        'Alih Fungsi'     => 'Alih Fungsi',
    ];

    public const YEAR_SELECT = [
        '2005' => '2005',
        '2006' => '2006',
        '2007' => '2007',
        '2008' => '2008',
        '2009' => '2009',
        '2010' => '2010',
        '2011' => '2011',
        '2012' => '2012',
        '2013' => '2013',
        '2014' => '2014',
        '2015' => '2015',
        '2016' => '2016',
        '2017' => '2017',
        '2018' => '2018',
        '2019' => '2019',
        '2020' => '2020',
        '2021' => '2021',
        '2022' => '2022',
        '2023' => '2023',
        '2024' => '2024',
    ];

    public const FUNDED_SELECT = [
        'APBD'               => 'APBD',
        'DAK Fisik'          => 'DAK Fisik',
        'APBN'               => 'APBN',
        'APBD Provinsi'      => 'APBD Provinsi',
        'CSR /Swasta'        => 'CSR / Swasta',
        'Swadaya Masyarakat' => 'Swadaya Masyarakat',
    ];

    public $table = 'builds';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'categories_id',
        'address',
        'kecamatans_id',
        'kelurahans_id',
        'lat',
        'lng',
        'year',
        'status',
        'funded',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = [
        'coordinate', 'map_popup_content',
    ];

    /**
     * Get outlet name_link attribute.
     *
     * @return string
     */
    public function getNameLinkAttribute()
    {
        $title = __('global.show_detail_title');
        $link = '<a href="' . route('admin.builds.show', $this) . '"';
        $link .= ' title="' . $title . '">';
        $link .= $this->id;
        $link .= '</a>';

        return $link;
    }

    public function getCoordinateAttribute()
    {
        if ($this->lat && $this->lng) {
            return $this->lat . ', ' . $this->lng;
        }
    }

    public function getMapPopupContentAttribute()
    {
        $mapPopupContent = '';
        $mapPopupContent .= '<div class="my-2"><strong>' . __('global.id_build') . ':</strong><br>' . $this->name_link . '</div>';
        $mapPopupContent .= '<div class="my-2"><strong>' . __('global.coordinates') . ':</strong><br>' . $this->coordinate . '</div>';

        return $mapPopupContent;
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function kecamatans()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatans_id');
    }

    public function kelurahans()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahans_id');
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function galleries()
    {
        return $this->hasMany(BuildGallery::class, 'build_id', 'id');
    }
}
