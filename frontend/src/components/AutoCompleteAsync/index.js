import React from "react";
import Downshift from "downshift";
import {Form, Spinner} from "react-bootstrap";

var TIME = null
const AutoCompleteAsync = ({handleOnSelect, handleSearch, handleOnBlur, handleOnChange, suggestions, value, searching = false, optionId = 'id', optionDisplay = 'value', optionSubDisplay = '', ...rest}) => {
    const inputOnChange = (event) => {
        if (handleOnChange) {
            handleOnChange(event.target.value)
        }
        if (event.target.value) {
            fetchSearch(event.target.value);
        }
    }

    const downshiftOnChange = (selectedValue) => {
        handleOnSelect(selectedValue)
    }

    const fetchSearch = (term) => {
        if (TIME) {
            clearTimeout(TIME)
        }
        TIME = setTimeout(() => {
            handleSearch(term)
        }, 500)
    }

    const fnOptionDisplay = (format) => (item) => {
        let value = ''
        if (typeof format === 'function') {
            value = format(item)
        } else {
            value = item[format]
        }
        return value
    }

    return (
        <Downshift
            onChange={downshiftOnChange}
            itemToString={item => (item ? item.title : "")}
        >
            {({
                  selectedItem,
                  getInputProps,
                  getItemProps,
                  highlightedIndex,
                  isOpen,
                  inputValue,
                  getLabelProps,
                  clearSelection
              }) => (
                <div>
                    <Form.Control
                        {...rest}
                        autoComplete={"new-password"}
                        {...getInputProps({
                            onChange: event => {
                                clearSelection()
                                inputOnChange(event)
                            },
                            onBlur: () => {
                                if (handleOnBlur) {
                                    handleOnBlur(selectedItem)
                                }
                            }
                        })}
                        value={inputValue || value}
                    />
                    {searching && <Spinner variant={'primary'} size={'xs'} animation="border" role="status" className={'float-right'}>
                        <span className="sr-only">Loading...</span>
                    </Spinner>}
                    {(isOpen && !searching) ? (
                        <ul className="list-group" style={{position: 'absolute', zIndex: 9999, width: '96%'}}>
                            {(suggestions || []).map((item, index) => (
                                <li
                                    className={`list-group-item list-group-item-action flex-column align-items-start ${highlightedIndex === index ? "active" : ""}`}
                                    {...getItemProps({key: index, index, item})}
                                    style={{
                                        fontWeight: selectedItem === item ? "bold" : "normal"
                                    }}
                                >
                                    <div>{fnOptionDisplay(optionDisplay)(item)}</div>
                                    {optionSubDisplay &&
                                    <small className={'text-muted'}>{fnOptionDisplay(optionSubDisplay)(item)}</small>}
                                </li>
                            ))}
                        </ul>
                    ) : null}
                </div>
            )}
        </Downshift>
    );
}

export default AutoCompleteAsync
