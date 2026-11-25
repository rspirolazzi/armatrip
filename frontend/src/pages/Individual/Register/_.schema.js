import * as Yup from 'yup';

const currentYear = (new Date()).getFullYear()
const maxYear = currentYear + 2
const schema = Yup.object({
    name: Yup.string()
        .required('Tu nombre es requerido'),
    email: Yup.string()
        .email('El formato del email no es correcto')
        .required('Tu email es requerido'),
    adults: Yup.number()
        .min(1, 'Adultos no puede ser menor a 1')
        .max(8, 'Adultos no puede ser mayor a 8')
        .integer('Adultos es numerico')
        .required('Adultos es requerido'),
    children: Yup.number()
        .min(0, 'Niños no puede ser menor a 0')
        .max(8, 'Niños no puede ser mayor a 8')
        .integer('Niños es numerico')
        .required('Niños es requerido'),
    babies: Yup.number()
        .min(0, 'Bebes no puede ser menor a 0')
        .max(8, 'Bebes no puede ser mayor a 8')
        .integer('Bebes es numerico')
        .required('Bebes es requerido'),
});
export default schema