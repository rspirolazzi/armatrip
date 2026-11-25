import PropTypes from 'prop-types'
import React, {useState} from 'react'
import {Button, Collapse} from "react-bootstrap"
import {selectActionForProduct} from "../../../../store/notification/utils"
import {notify} from "../../../../layouts/utils"

const Categories = ({onClickSelectProduct, items, title, multiple, actions, dispatchSetNotifications, handleIsDirty}) => {

    const [show, setShow] = useState(false)
    const [selected, setSelected] = useState(null)
    const [isDirty, setIsDirty] = useState(false)

    const showOrHide = () => {
        setShow(!show)
    }

    const handleClickSelect = (item, check, category_multiple) => {
        setIsDirty(true)
        if(handleIsDirty){
            handleIsDirty(true)
        }
        setSelected(item.id)
        onClickSelectProduct({
            selected: item.id,
            productsFromCategory: items,
            check,
            category_multiple,
        })
        let _actions = selectActionForProduct(actions, item, check)
        console.log('acciones', _actions, item, check)
        const showNotification = () => {
            if (_actions && _actions.length > 0) {
                console.log('se mostrara la accion', _actions[0])
                notify(_actions.pop(), (action) => {
                    console.log('Se cerro el mensaje', action)
                    showNotification()
                })
            } else {
                console.log('no hay acciones que mostrar para ', item, check)
            }
        }
        showNotification();
    }

    const faChecked = multiple ? 'fa-check-square-o' : 'fa-check-circle-o'
    const faNoChecked = multiple ? 'fa-square-o' : 'fa-circle-o'
    return <>
        <Button onClick={showOrHide} variant={"primary"} block
                className={"rounded-0 border-primary text-uppercase mt-1 font-size-sm text-white"}>{title} {isDirty?'*':''}<span
            className={"pull-right"}>{!show ? <i className="fa fa-angle-right"></i> :
            <i className="fa fa-angle-down"></i>}</span></Button>
        <Collapse in={show}>
            <div id="example-collapse-text" className={"text-center text-uppercase"}>
                <div className={'list-group'}>
                    {items.map((item, i) => <a
                        key={i}
                        onClick={() => handleClickSelect(item, !item.default, multiple)}
                        className={`rounded-0 border-0 text-uppercase my-1 ${item.default ? 'text-success' : 'text-primary'} font-size-sm`}>{item.name}
                        {item.default &&
                        <span className={"pull-right"}><i className={`fa ${faChecked}`}></i></span>}
                        {!item.default &&
                        <span className={"pull-right"}><i className={`fa ${faNoChecked}`}></i></span>}
                    </a>)}
                </div>
            </div>
        </Collapse>
    </>
}

export default Categories

Categories.propTypes = {
    items: PropTypes.any.isRequired,
    title: PropTypes.string.isRequired
}