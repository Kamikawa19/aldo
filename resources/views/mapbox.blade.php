@extends('layouts.apps')

@push('after-style')

    <script src='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css' rel='stylesheet' />

    <style>
        body { margin: 0; padding: 10; }
        #map { position: absolute; bottom: 0; width: 100%; }
    </style>

@endpush

@section('header')
@include('components.mapnav')
@endsection

@section('content')
<style>
    #menu {
    background: rgba(0, 0, 0, 0.25);
    color: white;
    display: block;
    position: absolute;
    z-index: 1;
    top: 120px;
    right: 10px;
    border-radius: 3px;
    width:250px;
    border: 1px solid rgba(0, 0, 0, 0.4);
    font-family: 'Open Sans', sans-serif;
    }
     
    #menu a {
    font-size: 13px;
    color: #404040;
    display: block;
    margin: 0;
    padding: 0;
    padding: 10px;
    text-decoration: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.25);
    text-align: center;
    }

    #menu a:last-child {
    border: none;
    }
     
    #menu a:hover {
    background-color: #f8f8f8;
    color: #404040;
    }
     
    #menu a.active {
    background-color: #3887be;
    color: #ffffff;
    }
     
    #menu a.active:hover {
    background: #3074a4;
    }
    </style>


    <div id="menu">
        <div>
            <input id="satellite-v9" type="radio" name="rtoggle" value="satellite">
            <label for="satellite-v9">Satellite</label>
        </div>
        <div>
        <input id="light-v10" type="radio" name="rtoggle" value="light">
        <label for="light-v10">Light</label>
        </div>
        <div>
            <input id="dark-v10" type="radio" name="rtoggle" value="dark" checked="checked">
            <label for="dark-v10">Dark</label>
        </div>
        <div>
            <input id="streets-v11" type="radio" name="rtoggle" value="streets">
            <label for="streets-v11">Streets</label>
        </div>
        <div>
            <input id="navigation-night-v1" type="radio" name="rtoggle" value="navigation-night-v1">
            <label for="navigation-night-v1">Navigation Night</label>
        </div>
    </div>
   
    <div id="map" role="map" height="100%" ></div>



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
<script>
    window.mapboxToken = "{{ env('MAPBOX_TOKEN') }}"
</script>

<script>
  

  let map = [];

   
  mapboxgl.accessToken = mapboxToken ;
    map = new mapboxgl.Map({
    style: 'mapbox://styles/mapbox/dark-v10',
    center: [114.6185566, -3.314771],
    zoom: 13.5,
    pitch: 45,
    bearing: -17.6,
    container: 'map',
    antialias: true,
    });

    const layerList = document.getElementById('menu');
    const inputs = layerList.getElementsByTagName('input');
    
    for (const input of inputs) {
        input.onclick = (layer) => {
        const layerId = layer.target.id;
        map.setStyle('mapbox://styles/mapbox/' + layerId);
        };
    }
    
    map.on('load', () => {
    // Insert the layer beneath any symbol layer.
    const layers = map.getStyle().layers;
    const labelLayerId = layers.find(
    (layer) => layer.type === 'symbol' && layer.layout['text-field']
    ).id;
    


    map.addLayer(
    {
    'id': 'add-3d-buildings',
    'source': 'composite',
    'source-layer': 'building',
    'filter': ['==', 'extrude', 'true'],
    'type': 'fill-extrusion',
    'minzoom': 15,
    'paint': {
    'fill-extrusion-color': '#aaa',
    

    'fill-extrusion-height': [
    'interpolate',
    ['linear'],
    ['zoom'],
    15,
    0,
    15.05,
    ['get', 'height']
    ],
    'fill-extrusion-base': [
    'interpolate',
    ['linear'],
    ['zoom'],
    15,
    0,
    15.05,
    ['get', 'min_height']
    ],
    'fill-extrusion-opacity': 0.6
    }
    },
    labelLayerId
    );
    });

    //whatever layers you want to toggle go in to this function
    toggleLayer(['add-3d-buildings'], '3D Building');
    toggleLayer([''], 'reciprocite toggle 2');

    function toggleLayer(ids, name) {
        var link = document.createElement('a');
        link.href = '#';
        link.className = 'active';
        link.textContent = name;

        link.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();
            for (layers in ids){
                var visibility = map.getLayoutProperty(ids[layers], 'visibility');
                if (visibility === 'visible') {
                    map.setLayoutProperty(ids[layers], 'visibility', 'none');
                    this.className = '';
                } else {
                    this.className = 'active';
                    map.setLayoutProperty(ids[layers], 'visibility', 'visible');
                }
            }

        };

        var layers = document.getElementById('menu');
        layers.appendChild(link);
    }

    let fullscreen = new mapboxgl.FullscreenControl();
    map.addControl(fullscreen, 'top-left');

    let geolocate = new mapboxgl.GeolocateControl({
    positionOptions: {
    enableHighAccuracy: true
    },
    trackUserLocation: true,
    showUserHeading: true
    });
    map.addControl(geolocate, 'top-left');

</script>

@endpush

    