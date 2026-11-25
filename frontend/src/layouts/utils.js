import {toast} from "react-toastify"
import React from "react"

const Msg = ({level, message}) => (
    <div className={`Toastify__toast-subbody bg-${level} text-white`} style={{
        width: '300px',
        height: '300px',
        borderRadius: '50%',
        textAlign: 'center',
        display: 'table-cell',
        verticalAlign: 'middle',
    }}>
        <div dangerouslySetInnerHTML={{__html: message}}/>
    </div>
)
export const notify = (notification, handleClose, delay=0) => {
    if (toast.isActive(notification.id)) {
        return
    }
    let toast_type = ''
    switch (notification.type) {
        case 'danger':
            toast_type = 'error'
            break
        default:
            toast_type = notification.type
            break
    }
    let config = {
        autoClose: notification.duration * 1000,
        position: toast.POSITION.BOTTOM_CENTER,
        delay: delay,
        type: toast_type,
        toastId: notification.id,
        //onOpen:() => this.props.dispatchRemove(notification.id),
        onClose: () => handleClose(notification),
    }
    if (notification.is_custom) {
        config.className = 'circle'
        config.position = toast.POSITION.TOP_CENTER

        toast(<Msg level={notification.type} message={notification.message}/>, config)
    } else {
        toast(<div dangerouslySetInnerHTML={{__html: notification.message}}/>, config)
    }
}