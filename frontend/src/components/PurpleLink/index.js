import PropTypes from 'prop-types'
import React from 'react'
import {Link} from "react-router-dom"

const PurpleLink =({to,rounded,size,variant, children,...rest})=><Link to={to} className={`btn btn-${variant} btn-${size} rounded-${rounded}`} {...rest}>{children}</Link>

PurpleLink.propTypes = {
  to: PropTypes.string.isRequired,
  rounded: PropTypes.string,
  size: PropTypes.string,
  variant: PropTypes.string
}

PurpleLink.defaultProps = {
  rounded: 'circle',
  size: 'lg',
  variant: 'accent'
}

export default PurpleLink