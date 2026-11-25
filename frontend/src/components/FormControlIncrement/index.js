import React, {useState} from 'react'
import {Button} from "react-bootstrap";

const FormControlIncrement = ({
                                  maxValue = 999, minValue = 0, step = 1, value, onChange = () => {
    }
                              }) => {
    const [inputValue, setInputValue] = useState(value || minValue || 0)
    const [inputValueTemp, setInputValueTemp] = useState(null)
    const upValue = () => {
        const new_value = inputValue + step
        setNewValue(new_value)
    }
    const downValue = () => {
        const new_value = inputValue - step
        setNewValue(new_value)
    }
    const isValidNewValue = (new_value) => {
        return (typeof new_value === 'number' && new_value <= maxValue && new_value >= minValue)
    }
    const setNewValue = (new_value) => {
        if (isValidNewValue(new_value)) {
            setInputValue(new_value)
            onChange(new_value)
        }

    }
    const _handleChange = (e) => {
        if (isValidNewValue(inputValue)) {
            setInputValueTemp(inputValue)
        }
        setInputValue(parseInt(e.target.value))
    }
    const _handleBlur = (e) => {
        if (isValidNewValue(inputValue)) {
            setNewValue(inputValue)
        } else {
            setNewValue(inputValueTemp)
        }
        setInputValueTemp(null)
    }
    return <div className={'d-flex'}>
        <Button disabled={inputValue === minValue} onClick={downValue} size={'sm'} variant={'link'}
                className={'text-info'}><i
            className={'fa fa-minus-circle'}></i></Button>
        <input type={'number'} onChange={_handleChange} onBlur={_handleBlur}
               className={'form-control input-sm text-center'}
               value={inputValue}/>
        <Button disabled={inputValue === maxValue} onClick={upValue} size={'sm'} variant={'link'}
                className={'text-info'}><i
            className={'fa fa-plus-circle'}></i></Button>
    </div>
}
export default FormControlIncrement