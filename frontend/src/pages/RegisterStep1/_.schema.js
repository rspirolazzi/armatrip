import * as Yup from 'yup';

const currentYear = (new Date()).getFullYear()
const maxYear = currentYear+2
const schema = Yup.object({
    name: Yup.string().required('Tu nombre es requerido'),
    province_code: Yup.string().required('De donde son? es requerido'),
    email: Yup.string()
        .email('El formato del email no es correcto')
        .required('Tu email es requerido'),
    school_showed:Yup.boolean(),
    school_name: Yup.string().when('school_showed', {is:true, then:Yup.string().required('Tu Colegio es requerido')}),
    school_city: Yup.string().when('school_showed', {is:true, then:Yup.string().required('Localidad es requerido')}),
    year: Yup.number()
        .min(currentYear-1, 'Año es requerido')
        .max(maxYear, 'El año no puede ser mayor a ' + maxYear)
        .integer('El año es numerico')
        .required('Año es requerido'),
    month: Yup.number()
        .min(1, 'El mes es requerido')
        .max(12, 'El mes no puede ser mayor a 12')
        .integer('El mes es numerico')
        .required('El mes es requerido'),
    biweekly: Yup.number()
        .min(1, 'La quincena es requerida')
        .max(2, 'La quincena no puede ser mayor a 2')
        .integer('La quincena es numerico')
        .required('La quincena es requerida'),
    qty: Yup.number()
        .min(1, 'Pasajeros no puede ser menor a 1')
        .max(200, 'Pasajeros no puede ser mayor a 200')
        .integer('Pasajeros es numerico').required('Pasajeros es requerido'),
});
export default schema