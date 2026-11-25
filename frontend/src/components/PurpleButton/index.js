import PropTypes from 'prop-types'
import React from 'react'
import {Button} from "react-bootstrap"

const PurpleButton =({rounded,size,variant, children,...rest})=><Button variant={variant} size={size} className={`rounded-${rounded}`} {...rest}>{children}</Button>

PurpleButton.propTypes = {
  rounded: PropTypes.string,
  size: PropTypes.string,
  variant: PropTypes.string
}

PurpleButton.defaultProps = {
  rounded: 'circle',
  size: 'lg',
  variant: 'accent'
}

export default PurpleButton