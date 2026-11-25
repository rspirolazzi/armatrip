import React from 'react'
import {Button} from "react-bootstrap";

const IndividualButton = ({variant = 'success', className = '', invert = false, children, ...rest}) => {
    const _classes = (className || '').split(' ')
    let classes = [...['rounded-pill', 'text-lowercase'], [..._classes]]

    if (invert) {
        classes.push('text-white')
    } else {
        classes.push('text-primary')
    }
    return <Button type={'submit'} variant={variant}
                   {...rest}
                   className={classes.join((' '))}>{children}</Button>
}

export default IndividualButton