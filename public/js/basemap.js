 /* Basemap Layers */
 let cartoLight = L.tileLayer("https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png", {
     maxZoom: 21,
     alt: "light basemap",
     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://cartodb.com/attributions">CartoDB</a>'
 });


 let mapboxStreet = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
     maxZoom: 21,
     maxNativeZoom: 19,
     alt: "Street basemap",
     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
         'Imagery Â© <a href="https://www.mapbox.com/">Mapbox</a>',
     id: 'mapbox/streets-v11',
     tileSize: 512,
     zoomOffset: -1
 });


 let mapboxOutdoor = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
     maxZoom: 21,
     maxNativeZoom: 19,
     alt: "outdoor basemap",
     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
         'Imagery Â© <a href="https://www.mapbox.com/">Mapbox</a>',
     id: 'mapbox/outdoors-v11'
 });


 let mapboxImagery = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
     maxZoom: 18,
     alt: "satellite basemap",
     attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
         'Imagery Â© <a href="https://www.mapbox.com/">Mapbox</a>',
     id: 'mapbox/satellite-streets-v11'
 });

 let googleSatellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
     maxZoom: 21,
     subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
     attribution: 'Maps Data: Google, Citra 2021 TerraMetrics, Data peta &copy; 2021',
 });

 let googleMaps = L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
     maxZoom: 20,
     subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
     attribution: 'Powered by Google , Data Peta: &copy; 2021',
 });

 //  let mapboxDark = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
 //      maxZoom: 21,
 //      alt: "dark basemap",
 //      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
 //          'Imagery Â© <a href="https://www.mapbox.com/">Mapbox</a>',
 //      id: 'mapbox/dark-v10'
 //  });

 let mapboxDark = L.tileLayer("https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png", {
     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
     subdomains: "abcd",
     maxZoom: 21,
     alt: "dark basemap",
 });


 let otopomap = L.tileLayer('//{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
     attribution: '© OpenStreetMap contributors. OpenTopoMap.org'
 });

 // basemap from esri
 let esriWorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
     //attribution esri
     attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
     //maximum zoom level
     maxZoom: 18,
     //subdomains
     subdomains: ['server', 'services'],
     //tile size
     tileSize: 512,
     //zoom offset
     zoomOffset: -1,
     //opacity
     opacity: 0.5,
     //zIndex,
     zIndex: 0
 });
