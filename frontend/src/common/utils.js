import {menu} from "../layouts/Main/_.routing"
import {menu as menuIndividualTourism} from "../layouts/IndividualTourism/_.routing"
import {_Utils} from "../store/core/_.utils";

export const isMobile = () => {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ) {
        return true;
    } else {
        return false;
    }
}


export const route = (key, params = null, absolute = false) => {
    return _route(key, params, absolute, menu);
}
export const routeIndividualTourism = (key, params = null, absolute = false) => {
    return _route(key, params, absolute, menuIndividualTourism);
}
const _route = (key, params = null, absolute = false, source) => {
    const regex = /\:([A-Za-z0-9]*)/gm;
    const str = `${source[key].path}`;
    let m, url = str, i = 0;

    while ((m = regex.exec(str)) !== null) {
        // This is necessary to avoid infinite loops with zero-width matches
        if (m.index === regex.lastIndex) {
            regex.lastIndex++;
        }
        url = url.replace(m[0], params[i]);
        i++
        // The result can be accessed through the `m`-variable.
        /*m.forEach((match, groupIndex) => {
            console.log(`Found match, group ${groupIndex}: ${match}`);
        });*/
    }
    if (absolute) {
        //url = window.location.origin + url
        url = `${_Utils().API.ABSOLUTE_URL}${url}`
        //url = 'http://rspirolazzi.com:7080' + url
    }
    return url;
}

export function truncateString(str, num=20, ellipses = '...') {
    // If the length of str is less than or equal to num
    // just return str--don't truncate it.
    if (str.length <= num) {
        return str
    }
    // Return str truncated with '...' concatenated to the end of str.
    return str.slice(0, num - ellipses.length) + ellipses
}