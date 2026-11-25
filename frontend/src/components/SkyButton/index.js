import PropTypes from 'prop-types'
import React from 'react'
import PurpleButton from "../PurpleButton"

const SkyButton = ({variant, children, ...rest}) => <PurpleButton variant={variant} {...rest}>{children}</PurpleButton>

SkyButton.propTypes = {
    rounded: PropTypes.string,
    size: PropTypes.string,
    variant: PropTypes.string
}

SkyButton.defaultProps = {
    variant: 'brand'
}

export default SkyButton