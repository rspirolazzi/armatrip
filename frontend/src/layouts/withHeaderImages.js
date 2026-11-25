import React, {Component, Fragment} from 'react';
import {ToastContainer} from "react-toastify"
import 'react-toastify/dist/ReactToastify.css';
import './ReactToastify.css';

let DELAY = 1000
export default function withHeaderImages(WrappedComponent) {
    class ArmatripComponent extends Component {
        static WrappedComponent = WrappedComponent;

        render() {
            return <Fragment>
                <WrappedComponent {...this.props} />
                <ToastContainer hideProgressBar={true}/>
            </Fragment>
        }
    }

    return ArmatripComponent
}