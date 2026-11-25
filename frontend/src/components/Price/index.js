import PropTypes from 'prop-types'
import React from "react"
import {formatMoney} from "../../store/core/_.utils"

const Price = ({value, currency, decimals, decimal_separator, thousands_separator, className}) => <>
    <small className={className} /*style={{fontSize: '1.1em'}}*/>{currency}</small>
    <b className={className} /*style={{fontSize: '1.3em'}}*/>{formatMoney(value, decimals, decimal_separator, thousands_separator)}</b>
</>
export default Price

Price.propTypes = {
    value: PropTypes.number.isRequired,
    decimal_separator: PropTypes.string.isRequired,
    decimals: PropTypes.number.isRequired,
    thousands_separator: PropTypes.string.isRequired,
    currency: PropTypes.string.isRequired,
}

Price.defaultProps = {
    value: 0,
    decimal_separator: ',',
    decimals: 0,
    thousands_separator: '.',
    currency: '$',
}