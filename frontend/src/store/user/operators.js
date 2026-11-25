import userService from '../../services/User'
import {_getUser, _sendContactCountry, _signIn, _verificationSMS} from './index'
import {setJWT} from "../../services/Base";

export const getUser = (jwt)=>_getUser(userService.getUser(jwt))
export const signInWithToken = (data) => _signIn(userService.registerWithToken(data))
export const signIn = (data) => _signIn(userService.register(data))
export const verificationSMS = (code, phone_code, phone_number) => _verificationSMS(userService.sendCodeSMS(code, phone_code, phone_number)
    .then(resp => {
        if(resp.success){
            setJWT(resp.data.token)
        }else{
            throw resp
        }
        return resp
    }))
export const sendContactCountry = (data) => _sendContactCountry(userService.sendContactCountry(data))