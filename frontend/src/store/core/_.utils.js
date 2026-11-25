export const _Utils = () => {
    return {
        API: {
            URL: process.env.REACT_APP_API_URL,
            GOOGLE_MAP_KEY: process.env.REACT_APP_API_GOOGLE_MAP_KEY,
            MERCADOPAGO_KEY: process.env.REACT_APP_API_MERCADOPAGO_KEY,
            BASE_PATH: process.env.REACT_APP_API_BASE_PATH,
            ABSOLUTE_URL: process.env.REACT_APP_API_ABSOLUTE_URL || window.location.origin,
            GOOGLE_RECAPTCHA_KEY: process.env.REACT_APP_API_GOOGLE_RECAPTCHA_KEY,
            VERSION: process.env.REACT_APP_API_VERSION,
            GOOGLE_ANALYTICS: process.env.REACT_APP_API_GOOGLE_ANALYTICS,
        },
        FACEBOOK: {
            APP_ID: process.env.REACT_APP_FACEBOOK_APP_ID,
        }
    }
}

export function formatMoney(value, decimals, decimal_separator, thousands_separator) {
    var c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals
    var d = decimal_separator === undefined ? "." : decimal_separator
    var t = thousands_separator === undefined ? "," : thousands_separator
    var s = value < 0 ? "-" : ""
    var i = String(parseInt(value = Math.abs(Number(value) || 0).toFixed(c)))
    // eslint-disable-next-line
    var j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(value - i).toFixed(c).slice(2) : "");
};

export const url_src = (src) => {
    if (src.startsWith('/')) {
        src = src.substr(1, src.length)
    }
    return `${_Utils().API.BASE_PATH}/${src}`
}
