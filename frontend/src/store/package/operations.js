import packageService from '../../services/Package'
import {_getPackage, _getPackages} from './index'

/* Operations */
export const getPackages = (store) => _getPackages(packageService.getPackages(store))
export const getPackage = (code) => _getPackage(packageService.getPackage(code))
export const getPackageDetail = (params = {}) => _getPackage(packageService.getPackageDetail(params).catch(err => {
    console.log('Error en paquete')
    return err
}))
