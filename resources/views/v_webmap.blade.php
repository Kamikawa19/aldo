@extends('layouts.apps')

@push('after-style')

    <x-leaflet></x-leaflet>

    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href= "{{ asset('css/L.Control.Layers.Tree.css') }}" />
    <link rel="stylesheet" href= "{{ asset('css/leaflet.plugin.css') }}" />
    <link rel="stylesheet" href= "{{ asset('css/iconLayers.css') }}" />

    
@endpush

@section('header')
@include('components.mapnav')
@endsection
    
@section('content')

<div class="preloader">
    <div class="loading">
        <img src="{{ asset('img/rotate.svg') }}" width=100px height=100px>
    </div>
</div>
    

<div id="map" style="width: 100%; height: 88vh; box-shadow: 0 0 3px rgba(0,0,0,0.5);"></div>
<div id="sidebar">
    <h1>leaflet-sidebar</h1>
</div>

  <!-- Modal -->
  <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="feature-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="feature-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="feature-info"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  @include('components.frontend.modal')
@endsection

@push('after-script')
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="{{ asset('js/basemap.js') }}"></script> 
<script src="{{ asset('js/iconLayers.js') }}"></script>
<script src="{{ asset('js/L.Control.Fullscreen.min.js') }}"></script>
<script src="{{ asset('js/L.Control.Locate.min.js') }}"></script>
<script src="{{ asset('js/L.Control.MousePosition.js') }}"></script>
<script src="{{ asset('js/leaflet-measure.js') }}"></script>
<script src="{{ asset('js/L.Control.Layers.Tree.js') }}"></script>
<script src="{{ asset('js/typeahead.bundle.min.js')}}"></script>
<script src="{{ asset('js/handlebars.min.js')}}"></script>
<script src="{{ asset('js/list.min.js')}}"></script>
<script src="{{ asset('js/TouchHover.js')}}"></script>
<script src="{{ asset('js/leaflet.scalefactor.min.js')}}"></script>
<script src="{{ asset('js/L.grid.js')}}"></script>

<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>

<script>
    $(document).ready(function(){
    $(".preloader").fadeOut(2000);
    })
</script>

<script>
$(document).ready(function(){
    setTimeout(function(){
    if (!sessionStorage.getItem('shown-modal')){
        $('#disclaimer').modal('show');
        sessionStorage.setItem('shown-modal', 'true');
    }},4000);
});
</script>

<script>
  let kecamatan = L.layerGroup();
  @foreach ($kecamatan as $data)
      let kecamatan{{ $data->id }} = L.layerGroup();
  @endforeach
    @foreach ($kategori as $data)
    let {{ $data->layer }}Layer = L.geoJson(null);
    let {{ $data->layer }} = L.layerGroup();
    @endforeach
</script>

<script>
  let map = [];
  // featureList,
  KelurahanSearch = [];
  // KecamatanSearch = [],
//   septiktankSearch = [],
//   mckplusSearch = [],
//   mckkomunalSearch = [],
//   ipalkomunalSearch = [],
//   mckumumSearch = [],
//   septikkomunalSearch = [];
// //untuk menampung array semua layer yang masuk ke POI di pojok kiri
// let sidebarLayers = [];
$(window).resize(function () {
  sizeLayerControl();
});
// $(document).on("click", ".feature-row", function (e) {
//   $(document).off("mouseout", ".feature-row", clearHighlight);
//   //sidebarClick(parseInt($(this).attr("id"), 10));
//   sidebarClick($(this).attr("lat"), $(this).attr("lng"));
// });
if (!("ontouchstart" in window)) {
  $(document).on("mouseover", ".feature-row", function (e) {
      highlight.clearLayers().addLayer(L.circleMarker([$(this).attr("lat"), $(this).attr("lng")], highlightStyle));
  });
}
$(document).on("mouseout", ".feature-row", clearHighlight);
// $("#list-btn").click(function () {
//   animateSidebar();
//   return false;
// });
// $("#sidebar-toggle-btn").click(function () {
//   animateSidebar();
//   return false;
// });
// $("#sidebar-hide-btn").click(function () {
//   animateSidebar();
//   return false;
// });
// function animateSidebar() {
//   $("#sidebar").animate({
//       width: "toggle"
//   }, 350, function () {
//       map.invalidateSize();
//   });
// }
function sizeLayerControl() {
  $(".leaflet-control-layers").css("max-height", $("#map").height() - 50);
}
function clearHighlight() {
  highlight.clearLayers();
}
// //function sidebarClick(id) {
// function sidebarClick(lat, lng) {
//     //let layer = markerClusters.getLayer(id);
//     //map.setView([layer.getLatLng().lat, layer.getLatLng().lng], 17);
//     map.setView([lat, lng], 21);
//     layer.fire("click");
//     /* Hide sidebar and go to the map on small screens */
//     if (document.body.clientWidth <= 767) {
//         $("#sidebar").hide();
//         map.invalidateSize();
//     }
// // }
// function syncSidebar() {
//     /* Empty sidebar features */
//     $("#feature-list tbody").empty();
//     /* Loop through mckplus layer and add only features which are in the map bounds */
//     mckplus.eachLayer(function (layer) {
//         if (map.hasLayer(mckplusLayer)) {
//             if (map.getBounds().contains(layer.getLatLng())) {
//                 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="{{ asset('img/mckplus.png') }}"></td><td class="feature-name">' + layer.feature.properties.ALAMAT + " Kel. " + layer.feature.properties.KELURAHAN + " Kec. " + layer.feature.properties.KECAMATAN + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
//             }
//         }
//     });
//     /* Loop through mckkomunal layer and add only features which are in the map bounds */
//     mckkomunal.eachLayer(function (layer) {
//         if (map.hasLayer(mckkomunalLayer)) {
//             if (map.getBounds().contains(layer.getLatLng())) {
//                 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="{{ asset('img/mckkomunal.png') }}"></td><td class="feature-name">' + layer.feature.properties.ALAMAT + " Kel. " + layer.feature.properties.KELURAHAN + " Kec. " + layer.feature.properties.KECAMATAN + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
//             }
//         }
//     });
//     /* Loop through ipalkomunal layer and add only features which are in the map bounds */
//     ipalkomunal.eachLayer(function (layer) {
//         if (map.hasLayer(ipalkomunalLayer)) {
//             if (map.getBounds().contains(layer.getLatLng())) {
//                 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="{{ asset('img/ipalkomunal.png') }}"></td><td class="feature-name">' + layer.feature.properties.ALAMAT + " Kel. " + layer.feature.properties.KELURAHAN + " Kec. " + layer.feature.properties.KECAMATAN + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
//             }
//         }
//     });
//     /* Loop through mck umum layer and add only features which are in the map bounds */
//     mckumum.eachLayer(function (layer) {
//         if (map.hasLayer(mckumumLayer)) {
//             if (map.getBounds().contains(layer.getLatLng())) {
//                 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="{{ asset('img/mckumum.png') }}"></td><td class="feature-name">' + layer.feature.properties.ALAMAT + " Kel. " + layer.feature.properties.KELURAHAN + " Kec. " + layer.feature.properties.KECAMATAN + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
//             }
//         }
//     });
//     /* Loop through septiktank komunal layer and add only features which are in the map bounds */
//     septikkomunal.eachLayer(function (layer) {
//         if (map.hasLayer(septikkomunalLayer)) {
//             if (map.getBounds().contains(layer.getLatLng())) {
//                 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="{{ asset('img/septictank.png') }}"></td><td class="feature-name">' + layer.feature.properties.Tahun + " KOTAKU - Kel. " + layer.feature.properties.KELURAHAN + " Kec. " + layer.feature.properties.KECAMATAN + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
//             }
//         }
//     });
    // /* Update list.js featureList */
    // featureList = new List("features", {
    //     valueNames: ["feature-name"]
    // });
    // featureList.sort("feature-name", {
    //     order: "asc"
    // });
// }
/* Overlay Layers */
let highlight = L.geoJson(null);
let highlightStyle = {
  stroke: false,
  fillColor: "#00FFFF",
  fillOpacity: 0.7,
  radius: 10
};
/*Kerawanan*/
let kerawananColors = {
  "Risiko Sangat Tinggi": "#fc0314",
  "Risiko Tinggi": "#fcf912",
  "Risiko Sedang": "#89fc3d",
  "Risiko Rendah": "#319df5"
};
// specify popup options 
let customOptions = {
  'className': 'custom'
}
function kerawananFeature(feature, layer) {
  let contentPopup = layer.feature.properties.KELURAHAN;
  let dataKerawanan = layer.feature.properties.KERAWANAN;
  layer.on({
      click: function (value) {
          layer.bindPopup('<div style=text-align:center;>' + '<p>' + 'Kel. ' + contentPopup + '</p>' +
              '<p>' + 'Area ' + dataKerawanan + ' Air Limbah' + '</p>' + '</div>', customOptions);
          layer.openPopup();
      },
      mouseover: function (value) {
          let layerKerawanan = value.target;
          layerKerawanan.setStyle({
              weight: 3,
              color: "#00FFFF",
              fillOpacity: 0.05,
              opacity: 1
          });
          if (!L.Browser.ie && !L.Browser.opera) {
              layerKerawanan.bringToFront();
          }
      },
      mouseout: function (value) {
          Kerawanan.resetStyle(value.target);
      }
  });
}
let Kerawanan = L.geoJson(null, {
  style: function (feature) {
      return {
          name: Kerawanan,
          weight: 2,
          color: "white",
          fillColor: kerawananColors[feature.properties.KERAWANAN],
          fillOpacity: 0.7,
          opacity: 1,
          width: 0.01,
          dashArray: '3',
          interactive: true
      };
  },
  onEachFeature: kerawananFeature
});
$.getJSON("{{ asset('data/kerawanan.geojson') }}", function (data) {
  Kerawanan.addData(data);
});
// get color depending on population density value
function getColor(jiwa) {
  return jiwa > 40000 ? '#800026' :
      jiwa > 20000 ? '#BD0026' :
      jiwa > 15000 ? '#FEB24C' :
      jiwa > 0 ? '#FED976' :
      '#FFEDA0';
}
function style(feature) {
  return {
      weight: 2,
      opacity: 1,
      color: 'white',
      dashArray: '3',
      fillOpacity: 0.7,
      fillColor: getColor(feature.properties.KEPADATAN)
  };
}
function highlightFeature(e) {
  let layer = e.target;
  layer.setStyle({
      weight: 5,
      color: '#666',
      dashArray: '',
      fillOpacity: 0.7
  });
  if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
      layer.bringToFront();
  }
  info.update(layer.feature.properties);
}
let dataKepadatan;
function resetHighlight(e) {
  dataKepadatan.resetStyle(e.target);
  info.update();
  infoChart.update();
}
function zoomToFeature(e) {
  map.fitBounds(e.target.getBounds());
}
function onEachFeature(feature, layer) {
  layer.on({
      mouseover: highlightFeature,
      mouseout: resetHighlight,
      click: zoomToFeature
  });
}
dataKepadatan = L.geoJson(null, {
  name: dataKepadatan,
  style: style,
  onEachFeature: onEachFeature
});
$.getJSON("{{ asset('data/kepadatan.geojson') }}", function (data) {
  dataKepadatan.addData(data);
});



/*DELINIASI KUMUH*/
let kumuh = L.geoJson(null, {
  style: function (feature) {
      return {
          color: "grey",
          fillColor: "magenta",
          fillOpacity: 0.5,
          opacity: 0.5,
          width: 0.001,
          clickable: true,
          title: feature.properties.KATEGORI,
          riseOnHover: true
      };
  },
  onEachFeature: function (feature, layer) {
      if (feature.properties) {
          let content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>KRITERIA KUMUH</th><td>" + feature.properties.KRITERIA_KUMUH + "<tr><th>LUASAN KUMUH (M<SUP>2</SUP>)</th><td>" + feature.properties.LUAS + "</td></tr>" + "<tr><th>KELURAHAN</th><td>" + feature.properties.KELURAHAN + "</td></tr>" + "<tr><th>RT</th><td>" + feature.properties.RT + "</td></tr>" + "</td></tr>" + "<table>";
          layer.on({
              click: function (e) {
                  $("#feature-title").html(feature.properties.KATEGORI);
                  $("#feature-info").html(content);
                  $("#featureModal").modal("show");
              }
          });
      }
      layer.on({
          mouseover: function (e) {
              let layer = e.target;
              layer.setStyle({
                  weight: 3,
                  color: "#00FFFF",
                  opacity: 1
              });
              if (!L.Browser.ie && !L.Browser.opera) {
                  layer.bringToFront();
              }
          },
          mouseout: function (e) {
              kumuh.resetStyle(e.target);
          },
          click: zoomToFeature
      });
  }
});
$.getJSON("{{ asset('data/kumuh.geojson') }}", function (data) {
  kumuh.addData(data);
});
let normal = L.geoJson(null);
$.getJSON("", function (data) {
  normal.addData(data);
});
function zoomToFeature(e) {
  map.fitBounds(e.target.getBounds());
}
let Kelurahan = L.geoJson(null, {
  style: function (feature) {
      return {
          color: "#42a7f5",
          fill: true,
          fillOpacity: 0,
          opacity: 0.3,
          width: 0.01,
          riseOnHover: false
      };
  },
  
  onEachFeature: function (feature, layer) {
      KelurahanSearch.push({
          name: layer.feature.properties.KELURAHAN,
          source: "Kelurahan",
          id: L.stamp(layer),
          bounds: layer.getBounds()
      });
      layer.on({
          mouseover: function (e) {
              let layer = e.target;
              layer.setStyle({
                  weight: 3,
                  color: "#00FFFF",
                  fillOpacity: 0.05,
                  opacity: 1
              });
              // if (!L.Browser.ie && !L.Browser.opera) {
              //     layer.bringToFront();
              // }
          },
          mouseout: function (e) {
              Kelurahan.resetStyle(e.target);
          },
          click: zoomToFeature,
      });
  }
});
$.getJSON("{{ asset('data/Kelurahan.geojson') }}", function (data) {
  Kelurahan.addData(data);
});
/*Septiktank Individual*/
let septiktank = L.geoJson(null, {
  style: function (feature) {
      return {
          color: "white",
          fillColor: "#03a82a",
          fillOpacity: 0.7,
          opacity: 0.5,
          width: 0.001,
          clickable: true,
          title: feature.properties.JENIS_SARANA,
          riseOnHover: true
      };
  },
  onEachFeature: function (feature, layer) {
      if (feature.properties) {
          let content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>JENIS SARANA</th><td>" + feature.properties.JENIS_SARANA + "<tr><th>KECAMATAN</th><td>" + feature.properties.KECAMATAN + "</td></tr>" + "<tr><th>KELURAHAN</th><td>" + feature.properties.KELURAHAN + "</td></tr>" + "<tr><th>ALAMAT</th><td>" + feature.properties.ALAMAT + "</td></tr>" + "</td></tr>" + "<table>";
          layer.on({
              click: function (e) {
                  $("#feature-title").html(feature.properties.JENIS_SARANA);
                  $("#feature-info").html(content);
                  $("#featureModal").modal("show");
              }
          });
      }
      layer.on({
          mouseover: function (e) {
              let layer = e.target;
              layer.setStyle({
                  weight: 3,
                  color: "#00FFFF",
                  opacity: 1
              });
              if (!L.Browser.ie && !L.Browser.opera) {
                  layer.bringToFront();
              }
          },
          mouseout: function (e) {
              septiktank.resetStyle(e.target);
          }
      });
  }
});
$.getJSON("{{ asset('data/septiktank.geojson') }}", function (data) {
  septiktank.addData(data);
});
/*Jaringan Air Limbah */
let jaringanAirlimbah = L.geoJson(null, {
  style: function (feature) {
      return {
          //color: jalColors[feature.properties.KONDISI],
          color: "grey",
          weight: 1,
          opacity: 1
      };
  },
  onEachFeature: function (feature, layer) {
      if (feature.properties) {
          let content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>SUMBER DANA</th><td>" + feature.properties.SUMBER_DANA + "</td></tr>" + "<tr><th>JENIS</th><td>" + feature.properties.JENIS + "</td></tr>" + "<tr><th>Diameter (mm)</th><td>" + feature.properties.Diameter + "</td></tr>" + "<table>";
          layer.on({
              click: function (e) {
                  $("#feature-title").html(feature.properties.GID);
                  $("#feature-info").html(content);
                  $("#featureModal").modal("show");
              }
          });
      }
      layer.on({
          mouseover: function (e) {
              let layer = e.target;
              layer.setStyle({
                  weight: 3,
                  color: "#00FFFF",
                  opacity: 1
              });
              if (!L.Browser.ie && !L.Browser.opera) {
                  layer.bringToFront();
              }
          },
          mouseout: function (e) {
              jaringanAirlimbah.resetStyle(e.target);
          }
      });
  }
});
$.getJSON("{{ asset('data/jaringan_limbah.geojson') }}", function (data) {
  jaringanAirlimbah.addData(data);
});
/* Single marker cluster layer to hold all clusters */
let markerClusters = new L.MarkerClusterGroup({
  spiderfyOnMaxZoom: true,
  showCoverageOnHover: false,
  zoomToBoundsOnClick: true,
  disableClusteringAtZoom: 17
});
map = L.map(document.getElementById('map'), {
  zoom: 12,
  center: [-3.314771, 114.6185566],
  layers: [mapboxDark, Kelurahan,
  markerClusters, highlight],
  zoomControl: false,
  fullscreenControl: false
});
let grid = L.grid().addTo(map);
let iconLayersControl = new L.Control.IconLayers(
        [{
            title: 'Greyscale',
            layer: cartoLight,
            icon: '{{ asset('img/basemap/greyscale.png') }}'
        },
        {
            title: 'Hybrid',
            layer: mapboxImagery,
            icon: '{{ asset('img/basemap/imagery.png') }}'
        },
        {
            title: 'Outdoor',
            layer: mapboxOutdoor,
            icon: '{{ asset('img/basemap/outdoor.png') }}'
        },
        {
            title: 'Google Maps',
            layer: googleMaps,
            icon: '{{ asset('img/basemap/googleMaps.png') }}'
        },
        {
            title: 'Google Imagery',
            layer: googleSatellite,
            icon: '{{ asset('img/basemap/googleSatellite.png') }}'
        },
        {
            title: 'Street Maps',
            layer: mapboxStreet,
            icon: '{{ asset('img/basemap/streetmap.png') }}'
        },
        {
            title: 'Topo Map',
            layer: otopomap,
            icon: '{{ asset('img/basemap/topomap.png') }}'
        },
        {
            title: 'Dark',
            layer: mapboxDark,
            icon: '{{ asset('img/basemap/dark.png') }}'
        },
        ],
        {
            position: 'bottomright',
            maxLayersInRow: 4
        });
iconLayersControl.addTo(map);
iconLayersControl.on('activelayerchange', function(e) {
        console.log('layer switched', e.layer);
  });
/* Layer control listeners that allow for a single markerClusters layer */
map.on("overlayadd", function (e) {
    if (e.layer === mckplusLayer) {
        markerClusters.addLayer(mckplus);
        // syncSidebar();
    }
    if (e.layer === mckkomunalLayer) {
        markerClusters.addLayer(mckkomunal);
        // syncSidebar();
    }
    if (e.layer === ipalkomunalLayer) {
        markerClusters.addLayer(ipalkomunal);
        // syncSidebar();
    }
    if (e.layer === mckumumLayer) {
        markerClusters.addLayer(mckumum);
        // syncSidebar();
    }
    if (e.layer === septikkomunalLayer) {
        markerClusters.addLayer(septikkomunal);
        // syncSidebar();
    }
    if (e.layer === ipalLayer) {
        map.addLayer(ipal);
    }
});
map.on("overlayremove", function (e) {
    if (e.layer === mckplusLayer) {
        markerClusters.removeLayer(mckplus);
        // syncSidebar();
    }
    if (e.layer === mckkomunalLayer) {
        markerClusters.removeLayer(mckkomunal);
        // syncSidebar();
    }
    if (e.layer === ipalkomunalLayer) {
        markerClusters.removeLayer(ipalkomunal);
        // syncSidebar();
    }
    if (e.layer === mckumumLayer) {
        markerClusters.removeLayer(mckumum);
        // syncSidebar();
    }
    if (e.layer === septikkomunalLayer) {
        markerClusters.removeLayer(septikkomunal);
        // syncSidebar();
    }
    if (e.layer === ipalLayer) {
        map.removeLayer(ipal);
    }
});
// /* Filter sidebar feature list to only show features in current map bounds */
// map.on("moveend", function (e) {
//     syncSidebar();
// });
/* Clear feature highlight when map is clicked */
map.on("click", function (e) {
  highlight.clearLayers();
});
let onClicked = function (e) {
  map.off('contextmenu', onClicked); //turn off listener for map click
  currentMarker = L.marker(e.latlng, {
      icon: L.icon({
          iconUrl: "{{ asset('img/favicon/favicon.ico') }}",
          iconAnchor: [12, 28],
          popupAnchor: [0, -25]
      })
  }).addTo(map).bindPopup('Koordinat titik ini di ' + '<br>Lattitude : ' + e.latlng.lat.toString() +
      '<br>' + 'Longitude : ' + e.latlng.lng.toString());
};
map.on('contextmenu', onClicked);
map.on('contextmenu', function () {
  map.removeLayer(currentMarker);
  map.on('contextmenu', onClicked);
});
if (L.Browser.touch) {
    L.control.touchHover().addTo(map);
}
L.Path.CLIP_PADDING = 0.12;
/*Angka Kepadatan Penduduk*/
let info = L.control({
    position: "bottomleft"
});
info.onAdd = function (map) {
    this._div = L.DomUtil.create('divPadat', 'info');
    this.update();
    return this._div;
};
info.update = function (props) {
    this._div.innerHTML = (props ?
        '<b>' + props.KELURAHAN + '</b><br />' + props.KEPADATAN + ' jiwa / Km<sup>2</sup>' :
        '<h6>Gerakkan Mouse Anda di atas layer</h6>');
};
// Add and remove info from layers
map.on('overlayadd', function (eventLayer) {
    if (eventLayer.name === "Kepadatan Penduduk") {
        info.addTo(this);
    }
});
map.on('overlayremove', function (eventLayer) {
    if (eventLayer.name === "Kepadatan Penduduk") {
        this.removeControl(info);
    }
});
// //Dashboard Kecamatan
// let infoChart = L.control({
//     position: "topleft"
// });
// infoChart.onAdd = function (map) {
//     this._div = L.DomUtil.create('divKec', 'info');
//     this.update();
//     return this._div;
// };
// infoChart.update = function (kec) {
//     this._div.innerHTML = (kec ?
//         '<div id="chartContainer"></div>' + '<b>' + kec.KECAMATAN + '</b>' + '<br>' + 'Tanpa Akses (%) :' + kec.TANPA_AKSES + '<br>' + 'Akses Dasar (%) : ' + kec.AKSES_DASAR + '<br>' + 'Akses Aman (%) : ' + kec.AKSES_AMAN + '<br>' :
//         '<h6>Gerakkan Mouse di atas layer</h6>');
// };
// // Add and remove info from layers
// map.on('overlayadd', function (eventLayer) {
//     if (eventLayer.name === "Kecamatan") {
//         infoChart.addTo(this);
//     }
// });
// map.on('overlayremove', function (eventLayer) {
//     if (eventLayer.name === "Kecamatan") {
//         this.removeControl(infoChart);
//     }
// });
// //Legenda Kecamatan//
// let kecLegend = L.control({
//     name: 'kecLegend',
//     position: 'bottomleft'
// });
// kecLegend.onAdd = function (map) {
//     let divKec = L.DomUtil.create("divKec", "info legend");
//     divKec.innerHTML += "<h5><b>Legenda :</b> Kecamatan</h5>";
//     divKec.innerHTML += '<i style="background: #ffb400"></i><span>Banjarmasin Barat</span><br>';
//     divKec.innerHTML += '<i style="background: #70a1d7"></i><span>Banjarmasin Selatan</span><br>';
//     divKec.innerHTML += '<i style="background: #a1de93"></i><span>Banjarmasin Tengah</span><br>';
//     divKec.innerHTML += '<i style="background: #f47c7c"></i><span>Banjarmasin Timur</span><br>';
//     divKec.innerHTML += '<i style="background: #f7f48b"></i><span>Banjarmasin Utara</span><br>';
//     return divKec;
// };
// // Add and remove legend from layers
// map.on('overlayadd', function (eventLayer) {
//     // Switch to the legend...
//     if (eventLayer.name === "Kecamatan") {
//         kecLegend.addTo(this);
//         this.removeControl(legend);
//         this.removeControl(info);
//     }
// });
// map.on('overlayremove', function (eventLayer) {
//     // Switch to the legend...
//     if (eventLayer.name === "Kecamatan") {
//         this.removeControl(kecLegend);
//     }
// });
//Legenda Kerawanan Air Limbah//
let rawanLegend = L.control({
    name: 'rawanLegend',
    position: 'bottomleft'
});
rawanLegend.onAdd = function (map) {
    let div = L.DomUtil.create("div", "info legend");
    div.innerHTML += "<h6><b>Area Risiko Air Limbah</b></h6>";
    div.innerHTML += '<i style="background: #319df5"></i><span>Risiko Rendah</span><br>';
    div.innerHTML += '<i style="background: #89fc3d"></i><span>Risiko Sedang</span><br>';
    div.innerHTML += '<i style="background: #fcf912"></i><span>Risiko Tinggi</span><br>';
    div.innerHTML += '<i style="background: #fc0314"></i><span>Risiko Sangat Tinggi</span><br>';
    return div;
};
// Add and remove legend from layers
map.on('baselayerchange', function (eventLayer) {
    // Switch to the legend...
    if (eventLayer.name === "Risiko Air Limbah Domestik") {
        rawanLegend.addTo(map);
    }
});
// map.on('overlayremove', function (eventLayer) {
//     // Switch to the legend...
//     if (eventLayer.name === "Risiko Air Limbah Domestik") {
//         this.removeControl(rawanLegend);
//     }
// });
//Legenda Kepadatan Penduduk//
// let legend = L.control({
//     position: 'bottomleft'
// });
// legend.onAdd = function (map) {
//     let title = '<h6>Kepadatan Penduduk (jiwa/km<sup>2</sup>) </h6>';
//     let divPadat = L.DomUtil.create('divPadat', 'info legend'),
//         grades = [0, 15000, 20000, 40000],
//         labels = [],
//         from, to;
//     for (let i = 0; i < grades.length; i++) {
//         from = grades[i];
//         to = grades[i + 1];
//         labels.push(
//             '<i style="background:' + getColor(from + 1) + '"></i> ' +
//             from + (to ? '&ndash;' + to : '+'));
//     }
//     divPadat.innerHTML = title + [labels.join('<br>')];
//     return divPadat;
// };
// Add and remove legend from layers
// map.on('overlayadd', function (eventLayer) {
//     // Switch to the legend...
//     if (eventLayer.name === "Kepadatan Penduduk") {
//         legend.addTo(this);
//         this.removeControl(kecLegend);
//         this.removeControl(infoChart);
//     }
// });
// map.on('overlayremove', function (eventLayer) {
//     // Switch to the legend...
//     if (eventLayer.name === "Kepadatan Penduduk") {
//         this.removeControl(legend);
//     }
// });
// /* Larger screens get expanded layer control and visible sidebar */
// if (document.body.clientWidth <= 1367) {
//     var isCollapsed = true;
// } else {
//     var isCollapsed = false;
// }
/*KECAMATAN*/
@foreach ($kecamatan as $data)
L.geoJson(<?= $data->geojson ?>, {
  style : function (feature) {
    return {
      color : 'white',
      fillColor : '{{ $data->color}}',
      fillOpacity : 1.0,
      dashArray: '3',
    };
},
}).addTo(kecamatan{{$data->id }});
@endforeach
/*MCK Plus*/
@foreach ($mckplus as $data)
L.marker([<?= $data->lat . ',' . $data->lng ?>], {
        icon: L.icon({
                iconUrl: '{{ asset( Storage::url($data->icon) ) }}',
                iconSize: [24, 36],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            riseOnHover: true
        }).addTo({{ $data->layer }}).on('click', function(e){
          let modalTitle = "{{ $data->type }}";
          let content = "<table class='table table-striped table-bordered table-condensed'><tr><th>KELURAHAN</th><td>" + "{{ $data->name  }}" + "<tr><th>ALAMAT</th><td>" + "{{ $data->address }}" +  "<tr><th>STATUS</th><td>" + "{{ $data->status }}" + "<tr><th colspan='2'><a href='{{ url('/details') }}/{{ $data->buildId }}' class='tooltip-test' title='Detail'>Lihat Selengkapnya</a></th></tr>" + "</table>";
            $("#feature-title").html(modalTitle);
            $("#feature-info").html(content);
            $('#featureModal').modal('show');
            highlight.clearLayers().addLayer(L.circleMarker(this.getLatLng(), highlightStyle));
          });
@endforeach
/*MCK Komunal*/
@foreach ($mckkomunal as $data)
L.marker([<?= $data->lat . ',' . $data->lng ?>], {
        icon: L.icon({
                iconUrl: '{{ asset( Storage::url($data->icon) ) }}',
                iconSize: [24, 36],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            riseOnHover: true
          }).addTo({{ $data->layer }}).on('click', function(e){
          let modalTitle = "{{ $data->type }}";
          let content = "<table class='table table-striped table-bordered table-condensed'><tr><th>KELURAHAN</th><td>" + "{{ $data->name  }}" + "<tr><th>ALAMAT</th><td>" + "{{ $data->address }}" +  "<tr><th>STATUS</th><td>" + "{{ $data->status }}" + "<tr><th colspan='2'><a href='{{ url('/details') }}/{{ $data->buildId }}' class='tooltip-test' title='Detail'>Lihat Selengkapnya</a></th></tr>" + "</table>";
            $("#feature-title").html(modalTitle);
            $("#feature-info").html(content);
            $('#featureModal').modal('show');
            highlight.clearLayers().addLayer(L.circleMarker(this.getLatLng(), highlightStyle));
          });
@endforeach
/*MCK Umum*/
@foreach ($mckumum as $data)
L.marker([<?= $data->lat . ',' . $data->lng ?>], {
        icon: L.icon({
                iconUrl: '{{ asset( Storage::url($data->icon) ) }}',
                iconSize: [24, 36],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            riseOnHover: true
          }).addTo({{ $data->layer }}).on('click', function(e){
          let modalTitle = "{{ $data->type }}";
          let content = "<table class='table table-striped table-bordered table-condensed'><tr><th>KELURAHAN</th><td>" + "{{ $data->name  }}" + "<tr><th>ALAMAT</th><td>" + "{{ $data->address }}" +  "<tr><th>STATUS</th><td>" + "{{ $data->status }}" + "<tr><th colspan='2'><a href='{{ url('/details') }}/{{ $data->buildId }}' class='tooltip-test' title='Detail'>Lihat Selengkapnya</a></th></tr>" + "</table>";
            $("#feature-title").html(modalTitle);
            $("#feature-info").html(content);
            $('#featureModal').modal('show');
            highlight.clearLayers().addLayer(L.circleMarker(this.getLatLng(), highlightStyle));
          });
@endforeach
/*IPAL Komunal*/
@foreach ($ipalkomunal as $data)
L.marker([<?= $data->lat . ',' . $data->lng ?>], {
        icon: L.icon({
                iconUrl: '{{ asset( Storage::url($data->icon) ) }}',
                iconSize: [24, 36],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            riseOnHover: true
          }).addTo({{ $data->layer }}).on('click', function(e){
          let modalTitle = "{{ $data->type }}";
          let content = "<table class='table table-striped table-bordered table-condensed'><tr><th>KELURAHAN</th><td>" + "{{ $data->name  }}" + "<tr><th>ALAMAT</th><td>" + "{{ $data->address }}" +  "<tr><th>STATUS</th><td>" + "{{ $data->status }}" + "<tr><th colspan='2'><a href='{{ url('/details') }}/{{ $data->buildId }}' class='tooltip-test' title='Detail'>Lihat Selengkapnya</a></th></tr>" + "</table>";
            $("#feature-title").html(modalTitle);
            $("#feature-info").html(content);
            $('#featureModal').modal('show');
            highlight.clearLayers().addLayer(L.circleMarker(this.getLatLng(), highlightStyle));
          });
@endforeach
/*IPAL PERUMDAPALD*/
@foreach ($ipal as $data)
L.marker([<?= $data->lat . ',' . $data->lng ?>], {
        icon: L.icon({
                iconUrl: '{{ asset( Storage::url($data->icon) ) }}',
                iconSize: [24, 36],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            riseOnHover: true
          }).addTo({{ $data->layer }}).on('click', function(e){
          let modalTitle = "{{ $data->ipalName }}";
          let content = "<table class='table table-striped table-bordered table-condensed'><tr><th>KELURAHAN</th><td>" + "{{ $data->kelName  }}" + "<tr><th>ALAMAT</th><td>" + "{{ $data->address }}" + "<tr><th>TAHUN OPERASI</th><td>" + "{{ $data->year }}" + "<tr><th>KAPASITAS</th><td>" + "{{ $data->capacity }} m<sup>3</sup>" + "/hari" + "<tr><th colspan='2'><a href='{{ route('detailipal', $data->ipaldId) }}' class='tooltip-test' title='Detail'>Lihat Selengkapnya</a></th></tr>" + "</table>";
            $("#feature-title").html(modalTitle);
            $("#feature-info").html(content);
            $('#featureModal').modal('show');
            highlight.clearLayers().addLayer(L.circleMarker(this.getLatLng(), highlightStyle));
          });
@endforeach
/*KOTAKU*/
// @foreach ($kotaku as $data)
// L.marker([<?= $data->lat . ',' . $data->lng ?>], {
//         icon: L.icon({
//                 iconUrl: '{{ asset( Storage::url($data->icon) ) }}',
//                 iconSize: [24, 28],
//                 iconAnchor: [12, 28],
//                 popupAnchor: [0, -25]
//             }),
//             riseOnHover: true
//         }).bindPopup('{{ $data->type}} KOTAKU').openPopup().addTo(septikkomunal);
// @endforeach
// /* Empty layer placeholder to add to layer control for listening when to add/remove KOTAKU to markerClusters layer */
septikkomunal = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "{{ asset('img/icon/septictank.png') }}",
                iconSize: [24, 36],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.JENIS_SARANA,
            riseOnHover: true
        });
    },
    onEachFeature: function (feature, layer) {
        if (feature.properties) {
            let content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>NAMA KSM</th><td>" + feature.properties.NAMA_KSM + "<tr><th>KECAMATAN</th><td>" + feature.properties.KECAMATAN + "</td></tr>" + "<tr><th>KELURAHAN</th><td>" + feature.properties.KELURAHAN + "</td></tr>" + "<tr><th>TAHUN</th><td>" + feature.properties.Tahun + "</td></tr>" + "<tr><th>SUMBER DATA</th><td>" + feature.properties.SUMBER + "</td></tr>" + "</td></tr>" + "<table>";
            layer.on({
                click: function (e) {
                    $("#feature-title").html(feature.properties.JENIS_SARANA);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
            // $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="{{ asset('img/septictank.png') }}"></td><td class="feature-name">' + layer.feature.properties.NAMA_KSM + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            // septikkomunalSearch.push({
            //     name: layer.feature.properties.JENIS_SARANA,
            //     address: layer.feature.properties.KELURAHAN,
            //     source: "septikkomunal",
            //     id: L.stamp(layer),
            //     lat: layer.feature.geometry.coordinates[1],
            //     lng: layer.feature.geometry.coordinates[0]
            // });
       }
    }
});
$.getJSON("{{ asset('data/kotaku.geojson') }}", function (data) {
    septikkomunal.addData(data);
});
  let baseTree = {
      label: '<b>{{ trans('cruds.maps.thematic') }}</b>',
      children: [
          {label: '&nbsp;Normal', layer: normal},
          {label: '&nbsp;{{ trans('cruds.maps.slum') }}', layer: kumuh},
          {label: '&nbsp;{{ trans('cruds.maps.risk') }}', layer: Kerawanan},
          {label: '&nbsp;{{ trans('cruds.maps.density') }}', layer: dataKepadatan},
      ]
  };  
      var hasAllUnSelected = function() {
          return function(ev, domNode, treeNode, map) {
              var anySelected = false;
              function iterate(node)
              {
                  if (node.layer && !node.radioGroup) {
                      anySelected = anySelected || map.hasLayer(node.layer);
                  }
                  if (node.children && !anySelected) {
                      node.children.forEach(function(element) { iterate(element); });
                  }
              }
              iterate(treeNode);
              return !anySelected;
          };
      };
      let overlayTree = {
          label: '<b>LAYER</b>',
          children : [
              {label: '<b>{{ trans('cruds.maps.utility') }}</b>',
              selectAllCheckbox: true,
              children: [
                  {label: 'KECAMATAN',
                      selectAllCheckbox: true,
                      children: [
                  @foreach ($kecamatan as $data)
                  {label: '{{ $data->name }}' , layer: kecamatan{{$data->id }}},
                  @endforeach
                      ]
                  },
                  {label: '&nbsp;KELURAHAN', layer: Kelurahan},
                  {label: '&nbsp;{{ trans('cruds.maps.pipe') }}', layer: jaringanAirlimbah},
              ]},
              {label: '<b>{{ trans('cruds.maps.asset') }}</b>',
                  selectAllCheckbox: true,
                  children: [
                    {label: "<img src='{{ asset('img/green.png') }}' width='24' height='24'>&nbsp;Tangki Septik Individual", layer: septiktank},
                    @foreach ($kategori as $data)
                    {label: "<img src='{{ asset( Storage::url($data->icon) ) }}' width='24' height='36'>&nbsp;{{ $data->type }}", layer: {{ $data->layer }}Layer},
                    @endforeach
                  ]
              }]
          };
      /* ends*/
      let ctl = L.control.layers.tree(baseTree,
              {
                  // namedToggle: true,
                  collapseAll: 'Collapse all',
                  // expandAll: 'Expand all',
                  collapsed: true,
              });
      ctl.addTo(map).collapseTree(true).expandSelected(false);
      ctl.setOverlayTree(overlayTree,).collapseTree(true).expandSelected(false);
/* Highlight search box text on click */
$("#searchbox").click(function () {
    $(this).select();
});
/* Prevent hitting enter from refreshing the page */
$("#searchbox").keypress(function (e) {
    if (e.which == 13) {
        e.preventDefault();
    }
});
$("#featureModal").on("hidden.bs.modal", function (e) {
    $(document).on("mouseout", ".feature-row", clearHighlight);
});
/* Typeahead search functionality */
$(document).one("ajaxStop", function () {
    $("#loading").hide();
    sizeLayerControl();
    /* Fit map to Kelurahan bounds */
    map.fitBounds(Kelurahan.getBounds());
    // featureList = new List("features", {
    //     valueNames: ["feature-name"]
    // });
    // featureList.sort("feature-name", {
    //     order: "asc"
    // });
    let KelurahanBH = new Bloodhound({
        name: "Kelurahan",
        datumTokenizer: function (d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: KelurahanSearch,
        limit: 10
    });
    // let KecamatanBH = new Bloodhound({
    //     name: "Kecamatan",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: KecamatanSearch,
    //     limit: 5
    // });
    // let septiktankBH = new Bloodhound({
    //     name: "Septiktank Individual",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: septiktankSearch,
    //     limit: 10
    // });
    // let mckplusBH = new Bloodhound({
    //     name: "MCK Plus",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: mckplusSearch,
    //     limit: 10
    // });
    // let mckkomunalBH = new Bloodhound({
    //     name: "MCK Komunal",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: mckkomunalSearch,
    //     limit: 10
    // });
    // let ipalkomunalBH = new Bloodhound({
    //     name: "IPAL Komunal",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: ipalkomunalSearch,
    //     limit: 10
    // });
    // let mckumumBH = new Bloodhound({
    //     name: "MCK Umum",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: mckumumSearch,
    //     limit: 10
    // });
    // let septikkomunalBH = new Bloodhound({
    //     name: "Septiktank Komunal (KOTAKU)",
    //     datumTokenizer: function (d) {
    //         return Bloodhound.tokenizers.whitespace(d.name);
    //     },
    //     queryTokenizer: Bloodhound.tokenizers.whitespace,
    //     local: septikkomunalSearch,
    //     limit: 100
    // });
    KelurahanBH.initialize();
//KecamatanBH.initialize();
    // septiktankBH.initialize();
    // mckplusBH.initialize();
    // mckkomunalBH.initialize();
    // ipalkomunalBH.initialize();
    // mckumumBH.initialize();
    // septikkomunalBH.initialize();
    /* instantiate the typeahead UI */
    $("#searchbox").typeahead({
        minLength: 3,
        highlight: true,
        hint: false
      },
      {
        name: "Kelurahan",
        displayKey: "name",
        source: KelurahanBH.ttAdapter(),
        templates: {header: "<h4 class='typeahead-header'>Kelurahan di Banjarmasin</h4>"}
      }).on("typeahead:selected", function (obj, datum) {
        if (datum.source === "Kelurahan") {
            map.fitBounds(datum.bounds);
        }
    }).on("typeahead:opened", function () {
        $(".navbar-collapse.in").css("max-height", $(document).height() - $(".navbar-header").height());
        $(".navbar-collapse.in").css("height", $(document).height() - $(".navbar-header").height());
    }).on("typeahead:closed", function () {
        $(".navbar-collapse.in").css("max-height", "");
        $(".navbar-collapse.in").css("height", "");
    });
    $(".twitter-typeahead").css("position", "static");
    $(".twitter-typeahead").css("display", "block");
  });
 /*Geocoder Searching*/
  L.Control.geocoder({
            position: "topleft",
            collapsed: true
        }).addTo(map);
/*Zoom Extends*/
  L.easyButton({
    states: [{
            stateName: 'zoom-to-default',        // name the state
            icon:      'fa-home',               // and define its properties
            title:     'zoom to Banjarmasin',      // like its title
            onClick: function(btn, map) {       // and its callback
                map.setView([-3.314771, 114.6185566], 12);
                btn.state('zoom-to-default');    // change state on click!
            }
       }]
}).addTo(map);
  /*Scale Map*/
  let scaleControl = L.control.scale({imperial: false}).addTo(map);
  // Scale Factor
  // let scaleFactor = L.control.scalefactor().addTo(map);
    /* Mouse Coordinate */
    let mousePosition = L.control.mousePosition().addTo(map);
  /*Fullscreen Control*/
  let fullscreenControl = L.control.fullscreen({
      position: "topleft"
  }).addTo(map);
  /* GPS enabled geolocation control set to follow the user's location */
  let locateControl = L.control.locate({
      position: "topleft",
      drawCircle: true,
      follow: true,
      setView: true,
      keepCurrentZoomLevel: true,
      markerStyle: {
          weight: 1,
          opacity: 0.8,
          fillOpacity: 0.8
      },
      circleStyle: {
          weight: 1,
          clickable: false
      },
      icon: "fa fa-location-arrow",
      metric: true,
      strings: {
          title: "{{ trans('global.location') }}",
          popup: "{{ trans('global.accuration') }}  {distance} {unit}  {{ trans('global.from_this_point') }}",
          outsideMapBoundsMsg: "{{ trans('global.outside_map') }}"
      },
      locateOptions: {
          maxZoom: 18,
          watch: true,
          enableHighAccuracy: true,
          maximumAge: 10000,
          timeout: 10000
      }
  }).addTo(map);
  /*Leaflet Measure*/
  function writeResults(results) {
  document.getElementById('eventoutput').innerHTML = JSON.stringify(
    {
      area: results.area,
      areaDisplay: results.areaDisplay,
      lastCoord: results.lastCoord,
      length: results.length,
      lengthDisplay: results.lengthDisplay,
      pointCount: results.pointCount,
      points: results.points
    },
      null,
      2
    );
  }
    let measureControl = new L.Control.Measure({
        position: 'topleft',
        primaryLengthUnit: 'kilometers',
        secondaryLengthUnit: 'meters',
        primaryAreaUnit: 'hectares',
        secondaryAreaUnit: 'sqmeters',
        activeColor:"#4fd65a",
        completedColor:"#5cf79a",
        decPoint: ',',
        thousandsSep: '.'});
    measureControl.addTo(map);
/* Leaflet patch to make layer control scrollable on touch browsers*/
let container = $(".leaflet-control-layers")[0];
if (!L.Browser.touch) {
  L.DomEvent
  .disableClickPropagation(container)
  .disableScrollPropagation(container);
} else {
  L.DomEvent.disableClickPropagation(container);
}

var sidebar = L.control.sidebar('sidebar', {
    position: 'left'
});

map.addControl(sidebar);



</script>

@endpush