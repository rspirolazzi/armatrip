import PropTypes from 'prop-types'
import React from 'react'
import {Form} from "react-bootstrap"

const SelectYear = ({placeholder, from, ...rest}) => {

    const options = () => {
        let options = []
        if (placeholder) {
            options.push({value: 0, name: placeholder})
        }
        if (from !== null && from.length > 0) {
            options = options.concat(from)
        }
        return options
    }

    return <Form.Control as="select" {...rest}>
        {options().map(option => <option value={option.value} key={option.value}>{option.name}</option>)}
    </Form.Control>
}

SelectYear.propTypes = {
    placeholder: PropTypes.string,
    from: PropTypes.array,
}

SelectYear.defaultProps = {
    from: null
}

export default SelectYear