import axios from "axios"
import qs from 'qs'
import {_Utils} from "../store/core/_.utils";
import {ACCESS_TOKEN} from "../common/constants";

export const setStorage = (key, value) => localStorage.setItem(key, value)
export const getStorage = (key) => localStorage.getItem(key)
export const delStorage = (key) => localStorage.removeItem(key)
export const delJWT = () => delStorage(ACCESS_TOKEN)
export const getJWT = () => getStorage(ACCESS_TOKEN)
export const setJWT = (jwt) => setStorage(ACCESS_TOKEN, jwt)

const CONFIG = _Utils()

if (!CONFIG.API)
    throw new Error('undefined API')


export default class Base {
    constructor() {
        if (!CONFIG.API)
            throw new Error('undefined API')

        this._URL = CONFIG.API.URL
        if (CONFIG.API.XDEBUG_SESSION_START) {
            this.XDEBUG_SESSION_START = CONFIG.API.XDEBUG_SESSION_START
        } else {
            this.XDEBUG_SESSION_START = null
        }
    }


    paramsStringify = (params) => qs.stringify(params)

    postWithJWT(uri, params = {}) {
        let url = this._URL + uri
        if (this.XDEBUG_SESSION_START) {
            url += '?XDEBUG_SESSION_START=' + this.XDEBUG_SESSION_START
        }
        params.token = getJWT()
        return this.processResponse(axios.post(url, this.paramsStringify(params)))
    }

    // El JWT viaja solo en el cuerpo de un POST: nunca en la URL, que queda en logs e historial.

    post(uri, params = {}) {
        let url = this._URL + uri
        if (this.XDEBUG_SESSION_START) {
            url += '?XDEBUG_SESSION_START=' + this.XDEBUG_SESSION_START
        }
        return this.processResponse(axios.post(url, this.paramsStringify(params)))
    }

    get(uri, params = {}) {
        let url = this._URL + uri
        if (this.XDEBUG_SESSION_START) {
            url += '?XDEBUG_SESSION_START=' + this.XDEBUG_SESSION_START
        }
        return this.processResponse(axios.get(url, {params}))
    }

    processResponse = axiosPromise => axiosPromise.then(resp => {
        if (resp.status === 200 && resp.data && resp.data.success === true) {
            return resp.data
        } else {
            throw resp.data
        }
    }).catch(err => {
        if (err.response && err.response.data) {
            return Promise.reject(err.response.data)
        }
        //throw err
        return Promise.reject(err)
    })
}
