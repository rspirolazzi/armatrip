import PropTypes from 'prop-types'
import React, {useState} from 'react'
import {Col, Row} from "react-bootstrap"

/*const SelectInput=({handleChange})=><div></div>
export default SelectInput

SelectInput.propTypes = {
  options: PropTypes.array.isRequired
}
*/
function SelectInput({options, handleChange}) {
    // Declare a new state variable, which we'll call "count"
    const [index, setIndex] = useState(0);

    function handleLeft() {
        const _index = index - 1
        if (_index >= 0) {
            setIndex(_index)
            handleChange(index)
        }
    }

    function handleRight() {
        const _index = index + 1
        if (_index < options.length) {
            setIndex(_index)
            handleChange(index)
        }
    }

    return (
        <Row>
            <Col xs={4} className={"text-right align-middle"}><a onClick={handleLeft}><i className={"fa fa-2x fa-angle-left"}></i></a></Col>
            <Col xs={4} className={"text-center align-middle"}><span className={"font-weight-bold"}>{options[index]}</span></Col>
            <Col xs={4} className={"text-left align-middle"}><a onClick={handleRight}><i className={"fa fa-2x fa-angle-right"}></i></a></Col>
        </Row>
    );
}

export default SelectInput

SelectInput.propTypes = {
    options: PropTypes.array.isRequired,
    handleChange: PropTypes.func.isRequired,

}