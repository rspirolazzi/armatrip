import {exception} from "react-ga";

export const TYPE_ERRORS = {
    'APRO': 'Pago aprobado.',
    'CONT': 'Pago pendiente.',
    'CALL': 'Rechazo llamar para autorizar.',
    'FUND': 'Rechazo por monto insuficiente.',
    'SECU': 'Rechazo por código de seguridad.',
    'EXPI': 'Rechazo por fecha de expiración.',
    'FORM': 'Rechazo por error en formulario.',
    'OTHE': 'Rechazo general.',
}
export const ERRORS = {
    '205': 'Ingresa el número de tu tarjeta.',
    '208': 'Elige un mes.',
    '209': 'Elige un año.',
    '212': 'Ingresa tu documento.',
    '213': 'Ingresa tu documento.',
    '214': 'Ingresa tu documento.',
    '220': 'Ingresa tu banco emisor.',
    '221': 'Ingresa el nombre y apellido.',
    '224': 'Ingresa el código de seguridad.',
    'E301': 'Hay algo mal en ese número de tarjeta. Vuelve a ingresarlo.',
    'E302': 'Revisa el código de seguridad.',
    '316': 'Ingresa un nombre válido.',
    '322': 'Revisa tu documento.',
    '323': 'Revisa tu documento.',
    '324': 'Revisa tu documento.',
    '325': 'Revisa la fecha.',
    '326': 'Revisa la fecha.',
    "011": "Esta accion no esta permitida en este momento. Por favor reintentelo mas tarde.",
}
export class MercadoPago {

    constructor(mp_public_key) {
        if (mp_public_key) {
            this.getMercadoPago().setPublishableKey(mp_public_key);
            this.getMercadoPago().getIdentificationTypes()
        } else {
            throw new exception('No se encontro la mp_public_key')
        }
    }

    getMessageFromResponse = ({ cause }) => {
        let result = cause.map(c => {
            if (c.code && ERRORS[c.code] !== undefined) {
                return `${ERRORS[c.code]} (${c.code})`
            }
            return ''
        })
        if (result.filter(r => r.length > 0).length == 0) {
            return ['Revisa los datos.']
        }
        else {
            return result
        }
    }

    getMercadoPago = () => window.Mercadopago

    addEvent = (el, eventName, handler) => {
        if (el.addEventListener) {
            el.addEventListener(eventName, handler)
        } else {
            el.attachEvent('on' + eventName, function () {
                handler.call(el)
            })
        }
    }
    getBin = (ccNumber) => ccNumber.replace(/[ .-]/g, '').slice(0, 6)

    guessingPaymentMethod = (event, callback) => {

        const value = event.target.value
        const bin = this.getBin(value)
        const thiz = this
        if (bin.length >= 6 && callback) {
            thiz.getMercadoPago().getPaymentMethod({
                "bin": bin
            }, thiz.setPaymentMethodInfo(callback))
        } else if (callback) {
            callback('')
        }
    }

    handleForGuessingPaymentMethod(_handle, callback) {
        const thiz = this
        return (e, v) => {

            thiz.guessingPaymentMethod(e, callback)
            return _handle(e)
        }
    }

    setPaymentMethodInfo = (callback) => {

        return (status, response) => {

            if (status === 200) {
                callback(response ? response[0] : null)
                return;
                /*if (document.querySelector("input[name=paymentMethodId]") === null) {
                    var paymentMethod = document.createElement('input')
                    paymentMethod.setAttribute('name', "paymentMethodId")
                    paymentMethod.setAttribute('type', "hidden")
                    paymentMethod.setAttribute('value', response ? response[0].id : '')

                    form.appendChild(paymentMethod)
                } else {
                    document.querySelector("input[name=paymentMethodId]").value = response ? response[0].id : ''
                }*/
            }
        }

    }
    createToken = ($form, responseHandler) => this.getMercadoPago().createToken($form, responseHandler)
    clearSession = () => this.getMercadoPago().clearSession()
}
