import * as Yup from 'yup';

const schema = Yup.object({
    cardNumber: Yup.string()
        .label('Número de tarjeta')
        .min(4)
        .max(16)
        .required('Número de tarjeta es requerido')
    ,
    expiration: Yup.string()
        .typeError('No es una fecha valida. Ejemplo: MM/AA')
        .max(5, 'No es una fecha valida. Ejemplo: MM/AA')
        .matches(
            /([0-9]{2})\/([0-9]{2})/,
            'No es una fecha valida. Ejemplo: MM/AA'
        )
        .required('Fecha de exp (MM/YY) es requerido')
    ,
    securityCode: Yup.string()
        .label('Cod Seg')
        .min(3)
        .max(4)
        .required('Cod Seg es requerido')
    ,
    docType: Yup.string()
        .label('Tipo')
        .required('Tipo es requerido')
    ,
    docNumber: Yup.string()
        .label('Número')
        .required('Número es requerido')
    ,
    cardholderName: Yup.string()
        .label('Nombre del titular')
        .required('Nombre del titular es requerido')
    ,
});
export default schema