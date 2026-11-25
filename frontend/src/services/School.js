import Base from './Base'

class SchoolService extends Base {

    getSchools(term, province='') {
        try {
            return this.get(`schools/${term}?province=${province}`)
        } catch (e) {
            //console.error(e)
            throw e
        }
    }
}

export default new SchoolService()