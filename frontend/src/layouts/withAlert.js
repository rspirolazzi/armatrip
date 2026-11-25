import React, {Component} from 'react';
import {bindActionCreators, compose} from 'redux';
import {connect} from 'react-redux';
import {isLoadingSelector, modalObjectSelector} from "../store/core/selectors"
import {Button, Modal} from "react-bootstrap"
import {_hideAlert} from "../store/core"
import {url_src} from "../store/core/_.utils"

export default function withAlert(WrappedComponent) {
    class ArmatripComponent extends Component {
        static WrappedComponent = WrappedComponent;

        constructor(props) {
            super(props);
            this.handleClose = this.handleClose.bind(this)
        }


        handleClose = () => {
            this.props.dispatchHideAlert()
        }

        render() {
            const {
                isLoading,
                modal,
            } = this.props;
            return <>
                {isLoading && <div className="h-100 w-100 text-center position-fixed bg-loading overlay-content">
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col-12 p-0">
                                <div className="min-vh-100 text-center m-0 d-flex flex-column justify-content-center">
                                    <div>
                                        <img src={url_src("img/load_200h.png")} className="pb-0 pt-5 px-5"/>
                                        <div className="spinner">
                                            <div className="bounce1"></div>
                                            <div className="bounce2"></div>
                                            <div className="bounce3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>}
                <Modal show={modal.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>{modal.title}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>{modal.message}</Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Cerrar
                        </Button>
                    </Modal.Footer>
                </Modal>
                <WrappedComponent {...this.props} />
            </>
        }
    }

    const mapStateToProps = state => ({
        isLoading: isLoadingSelector(state),
        modal: modalObjectSelector(state),
    });

    const mapDispatchToProps = (dispatch, props) => bindActionCreators({
        dispatchHideAlert: _hideAlert,
    }, dispatch)


    return compose(
        connect(
            mapStateToProps,
            mapDispatchToProps
        )
    )(ArmatripComponent);
}