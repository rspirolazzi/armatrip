import schoolService from '../../services/School'
import {_getSchools} from './index'

export const getSchools = (term, province = '') => _getSchools(schoolService.getSchools(term, province))