import * as Yup from 'yup';

const schema = Yup.object({
    phone_code: Yup.number()
        .integer('Código de Área es númerico')
        .min(10, 'Como minimo 2 digitos')
        .max(9999, 'Como maximo 4 digitos')
        .required('Código de Área es requerido'),
    phone_number: Yup.number()
        .integer('Número de teléfono es númerico')
        .min(100000, 'El celular debe tener como minimo 6 digitos')
        .max(99999999, 'El celular debe tener como maximo 8 digitos')
        .required('Número de teléfono es requerido'),
});
export default schema