import React, {Component, Fragment} from 'react';
import {bindActionCreators, compose} from 'redux';
import {connect} from 'react-redux';
import {_clearNotification, _notificationRemove} from "../store/notification"
import {getOneNotificationSelector} from "../store/notification/selectors"
import {ToastContainer} from "react-toastify"
import 'react-toastify/dist/ReactToastify.css';
import './ReactToastify.css';
import {notify} from './utils'

let DELAY = 1000
export default function withNotification(WrappedComponent) {
    class ArmatripComponent extends Component {
        static WrappedComponent = WrappedComponent;

        constructor(props) {
            super(props);
            this.handleClose = this.handleClose.bind(this)
            this.state = {
                notifications: [],
            }
        }

        handleClose = () => {
            this.props.dispatchClear()
        }

        render() {
            const {notification} = this.props
            if (notification) {
                notify(notification, (_notification) => this.props.dispatchRemove(_notification.id), DELAY)
            }
            return <Fragment>
                <WrappedComponent {...this.props} />
                <ToastContainer hideProgressBar={true}/>
            </Fragment>
        }
    }

    const mapStateToProps = (state, props) => ({
        notification: getOneNotificationSelector(state, props),
    });

    const mapDispatchToProps = (dispatch, props) => bindActionCreators({
        dispatchClear: _clearNotification,
        dispatchRemove: _notificationRemove,
    }, dispatch)

    return compose(
        connect(
            mapStateToProps,
            mapDispatchToProps
        )
    )(ArmatripComponent);
}