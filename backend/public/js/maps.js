var ovelay_complete = null;
var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
var geocoder
var map

// Define the LatLng coordinates for the polygon.
var areaCoords = store.map_area || null;


function deleteAllShape() {
    if (ovelay_complete != null) {
        ovelay_complete.setMap(null);
    }
    setCoordinates({})
    ovelay_complete = null;
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}

function clearMessage() {
    $('#message')
        .removeClass('alert-danger')
        .removeClass('alert-success')
        .addClass('hide')
        .find('> span').text('')
}

function setMessageSuccess(message) {
    $('#message')
        .removeClass('alert-danger')
        .removeClass('hide')
        .addClass('alert-success')
        .find('> span').text(message)
}

function setMessageError(message) {
    $('#message')
        .addClass('alert-danger')
        .removeClass('hide')
        .removeClass('alert-success')
        .find('> span').text(message)
}

function setCoordinates(coordinates) {
    $('#map_area').val(JSON.stringify(coordinates))
}

function inside(point, vs) {
    // ra   y-casting algorithm based on
    // http://www.ecse.rpi.edu/Homepages/wrf/Research/Short_Notes/pnpoly.html

    var x = point[0], y = point[1];

    var inside = false;
    for (var i = 0, j = vs.length - 1; i < vs.length; j = i++) {
        var xi = vs[i][0], yi = vs[i][1];
        var xj = vs[j][0], yj = vs[j][1];

        var intersect = ((yi > y) != (yj > y))
            && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
        if (intersect) inside = !inside;
    }

    return inside;
};
// This example requires the Drawing library. Include the libraries=drawing
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">
function rad2degr(rad) {
    return rad * 180 / Math.PI;
}

function degr2rad(degr) {
    return degr * Math.PI / 180;
}

/**
 * @param latLngInDeg array of arrays with latitude and longtitude
 *   pairs in degrees. e.g. [[latitude1, longtitude1], [latitude2
 *   [longtitude2] ...]
 *
 * @return array with the center latitude longtitude pairs in
 *   degrees.
 */
function getLatLngCenter(latLngInDegr) {
    var LATIDX = 0;
    var LNGIDX = 1;
    var sumX = 0;
    var sumY = 0;
    var sumZ = 0;

    for (var i = 0; i < latLngInDegr.length; i++) {
        var lat = degr2rad(latLngInDegr[i][LATIDX]);
        var lng = degr2rad(latLngInDegr[i][LNGIDX]);
        // sum of cartesian coordinates
        sumX += Math.cos(lat) * Math.cos(lng);
        sumY += Math.cos(lat) * Math.sin(lng);
        sumZ += Math.sin(lat);
    }

    var avgX = sumX / latLngInDegr.length;
    var avgY = sumY / latLngInDegr.length;
    var avgZ = sumZ / latLngInDegr.length;

    // convert average x, y, z coordinate to latitude and longtitude
    var lng = Math.atan2(avgY, avgX);
    var hyp = Math.sqrt(avgX * avgX + avgY * avgY);
    var lat = Math.atan2(avgZ, hyp);

    return ([rad2degr(lat), rad2degr(lng)]);
}

function initialize() {
    geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementById('map'), {
        center: storeLocation,
        zoom: 15,
        disableDefaultUI: true,
        zoomControl: true
    });
    var infoWindow = new google.maps.InfoWindow;
    var polyOptions = {
        strokeWeight: 0,
        fillOpacity: 0.45,
        editable: true
    };
    var drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        markerOptions: {
            draggable: true,
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: ['polygon']
        },
        drawingControlOptions: {
            drawingModes: ['polygon']
        },
        polylineOptions: {
            editable: true
        },
        polygonOptions: polyOptions,
        map: map,
    });
    drawingManager.setMap(map);

    google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
        setMessageSuccess('Se completo con éxito la delimitación de area')
        var coordinates = polygon.getPath().getArray().map(function (v) {
            return {lat: v.lat(), lng: v.lng(),}
        });
        setCoordinates(coordinates)
    });

    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
        clearMessage()
        deleteAllShape()
        event.overlay.overlayType = event.type;
        ovelay_complete = event.overlay;
        console.log('overlay', ovelay_complete)
        drawingManager.setDrawingMode(null)

        google.maps.event.addListener(event.overlay.getPath(), 'insert_at', function (i, _e) {
            var coordinates = this.j.map(function (v) {
                return {lat: v.lat(), lng: v.lng(),}
            });
            setCoordinates(coordinates)
        });
        google.maps.event.addListener(event.overlay.getPath(), 'remove_at', function (i, _e) {
            var coordinates = this.j.map(function (v) {
                return {lat: v.lat(), lng: v.lng(),}
            });
            setCoordinates(coordinates)
        });
        google.maps.event.addListener(event.overlay.getPath(), 'set_at', function (i, _e) {
            var coordinates = this.j.map(function (v) {
                return {lat: v.lat(), lng: v.lng(),}
            });
            setCoordinates(coordinates)
        });
    });

    google.maps.event.addDomListener(document.getElementById('delete-all-button'), 'click', deleteAllShape);

    if (storeLocation) {
        map.setCenter(storeLocation);
    } else if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Sucursal: ' + store.name);
            infoWindow.open(map);
            map.setCenter(pos);
            storeLocation = pos
        }, function () {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    if (stores && Array.isArray(stores) && stores.length > 0) {
        var _infoWindowStore = new google.maps.InfoWindow();

        for (var i = 0; i < stores.length; i++) {
            var _store = stores[i]
            var contentString = '<div id="content">' +
                '<h2>' + _store.name + (_store.active ? '' : '(no activada)') + '</h2>' +
                '<p>' + _store.telephone + '</p>' +
                '<p>' + _store.address + '</p>';
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(_store.lat), lng: parseFloat(_store.lon)},
                map: map,
                label: _store.name.substr(0, 1),
                title: 'Sucursal: ' + _store.name,
                contentString: contentString,
            });

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    _infoWindowStore.setContent(marker.contentString);
                    _infoWindowStore.open(map, marker);
                }
            })(marker, i));
        }
    }
    if (areaCoords) {
        // Construct the polygon.
        ovelay_complete = new google.maps.Polygon({
            paths: areaCoords,
            editable: true,
        });
        ovelay_complete.setMap(map);
        setCoordinates(areaCoords);
    }
}