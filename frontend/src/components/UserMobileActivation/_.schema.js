import * as Yup from 'yup';

const schema = Yup.object({
    code: Yup.string().required('El código es requerido'),
});
export default schema