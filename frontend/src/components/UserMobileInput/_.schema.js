import * as Yup from 'yup';

const Schema = Yup.object({
    phone_code: Yup.number()
        .integer('Código de Área es númerico')
        .min(10,'Como minimo 2 digitos')
        .max(999,'Como maximo 3 digitos')
        .required('Código de Área es requerido'),
    phone_number: Yup.number()
        .integer('Número de teléfono es númerico')
        .min(10000000,'El celular debe tener 8 numeros')
        .max(99999999,'El celular debe tener 8 numeros')
        .required('Número de teléfono es requerido'),
});
export default Schema