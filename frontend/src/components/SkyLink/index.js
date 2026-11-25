import PropTypes from 'prop-types'
import React from 'react'
import PurpleLink from "../PurpleLink"

const SkyLink =({to,variant, children,...rest})=><PurpleLink to={to} variant={variant} {...rest}>{children}</PurpleLink>

SkyLink.propTypes = {
  to: PropTypes.string.isRequired,
  rounded: PropTypes.string,
  size: PropTypes.string,
  variant: PropTypes.string
}

SkyLink.defaultProps = {
  variant: 'brand'
}

export default SkyLink