import _ from 'lodash'
/*// Get User's Coordinate from their Browser
window.onload = function() {
    // HTML5/W3C Geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(UserLocation);
    }
    // Default to Washington, DC
    else
        NearestCity(38.8951, -77.0367);
}*/

// Callback function for asynchronous call to HTML5 geolocation
export function UserLocation(position) {
    nearestStore(position.coords.latitude, position.coords.longitude);
}


// Convert Degress to Radians
function Deg2Rad(deg) {
    return deg * Math.PI / 180;
}

export const todayDelivery = (store) => {
    const {deliveryTimes} = store || {}
    const {type} = deliveryTimes || {}
    let value = {}
    if (type === 'all') {
        value = deliveryTimes[type]
    }
    return value
}
export const deliveryJsonToDateFormat = (value) => {
    if (value === null || value === undefined || value === '' || value.start_hour === undefined || value.start_min === undefined || value.end_hour === undefined || value.end_min === undefined) {
        return ''
    }
    return `de ${value.start_hour.toString().padStart(2, '0')}:${value.start_min.toString().padEnd(2, '0')}hs a ${value.end_hour.toString().padStart(2, '0')}:${value.end_min.toString().padEnd(2, '0')}hs.`
}

function PythagorasEquirectangular(lat1, lon1, lat2, lon2) {
    lat1 = Deg2Rad(lat1);
    lat2 = Deg2Rad(lat2);
    lon1 = Deg2Rad(lon1);
    lon2 = Deg2Rad(lon2);
    var R = 6371; // km
    var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
    var y = (lat2 - lat1);
    var d = Math.sqrt(x * x + y * y) * R;
    return d;
}

export function searchInsideCoords(latitude, longitude, stores) {
    var count = stores.length, i = 0, store = false
    while (i < count && store === false) {
        if(stores[i].map_area && Array.isArray(stores[i].map_area) && stores[i].map_area.length>0){
            if(inside(latitude, longitude, stores[i].map_area)){
                store = stores[i]
            }
        }
        i++
    }
    return store
}

export function nearestStore(latitude, longitude, stores) {
    let mindif = 99999;
    let closest;

    var storesWithOutMapDefined = stores.filter(s=>s.map_area === undefined || s.map_area === null || s.map_area.length===0)

    for (let index = 0; index < storesWithOutMapDefined.length; ++index) {
        var store = storesWithOutMapDefined[index] || {}

        let dif = PythagorasEquirectangular(latitude, longitude, store.lat, store.lon);
        if (dif < mindif) {
            closest = index;
            mindif = dif;
        }
    }
    let distanceInKM = getDistanceFromLatLonInKm(latitude, longitude, storesWithOutMapDefined[closest].lat, storesWithOutMapDefined[closest].lon)
    ////console.log('Sucursal encontrada', storesWithOutMapDefined[closest])
    //console.log('Distancia de la sucursal en KM', distanceInKM)
    if (distanceInKM <= storesWithOutMapDefined[closest].map_distance) {
        return storesWithOutMapDefined[closest]
    }
    return null
}

export function getRangeHours(start, end, interval) {
    function getMinutes(time) {
        var a = time.split(':').map(Number);
        return a[0] * 60 + a[1];
    }

    function getTime(m) {
        var h = m / 60 | 0;
        m %= 60;
        return h + ':' + (m < 10 ? '0' + m : m);
    }

    var r = [],
        startM = getMinutes(start),
        endM = getMinutes(end);

    while (startM + interval <= endM) {
        r.push(getTime(startM) + ' - ' + getTime(startM + interval));
        startM += interval;
    }
    return r;
}

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
    var R = 6371; // Radius of the earth in km
    var dLat = deg2rad(lat2 - lat1);  // deg2rad below
    var dLon = deg2rad(lon2 - lon1);
    var a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2)
    ;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c; // Distance in km
    return d;
}

function deg2rad(deg) {
    return deg * (Math.PI / 180)
}


function inside(latitude, longitude, coords) {

    var inside = false;
    for (var i = 0, j = coords.length - 1; i < coords.length; j = i++) {
        var xi = coords[i].lat, yi = coords[i].lng;
        var xj = coords[j].lat, yj = coords[j].lng;

        var intersect = ((yi > longitude) != (yj > longitude))
            && (latitude < (xj - xi) * (longitude - yi) / (yj - yi) + xi);
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

export const menu = stores=>{
    var stateOrder = _.orderBy(stores, 'name')
    var stateGrouped = _.groupBy(stateOrder, 'state')
    var  data =  Object.keys(stateGrouped).map(key => {
        const {state} = stateGrouped[key][0]
        return {name:state, stores: stateGrouped[key]}
    })
    return data
}

export const getProducts = (products, excluded_children = []) => {
    let resultProducts = []
    _.forEach(products.filter(p=>p.children.length>0).map(p=>p.children), function(value){
        _.forEach(value, function(f){
            resultProducts.push(f)
        })
    })
    resultProducts = _.uniqBy(resultProducts, (e)=>e.id);
    if (excluded_children !== null && Array.isArray(excluded_children)) {
        resultProducts = _.differenceBy(resultProducts, excluded_children, 'id')
    }
    return resultProducts

}
