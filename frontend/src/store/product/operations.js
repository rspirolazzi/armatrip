import productService from '../../services/Product'
import {_getProduct, _getProducts} from './index'

/* Operations */
export const getProducts = () => _getProducts(productService.getProducts())
export const getProduct = (code) => _getProduct(productService.getProduct(code))
