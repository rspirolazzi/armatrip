import React, {useState} from "react"
import {Button, ButtonGroup, Col, Container, Modal, Row} from "react-bootstrap"
import {getPackageSelector} from "../../store/package/selectors";
import {connect} from "react-redux";
import {url_src} from "../../store/core/_.utils";

function FloatButtonAction({product}) {
    const [show, setShow] = useState('');

    const handleShow = value=>{
        setShow(value)
        window.scrollTo(0, 0)
    }

    const handleClose = () => setShow('');

    const {more_info, photos, videos, name} = product
    if ((more_info === undefined || more_info === null) && (photos === undefined || photos === null || photos.length === 0) && (videos === undefined || videos === null || videos.length === 0)) {
        return null
    }
    return (
        <>
            <div className={'fab shadow-lg mb-2 py-4 bg-white rounded rounded-lg'}>
                <ButtonGroup vertical>
                    <Button variant={'link'} xs className={'text-muted'}><img width={'42px'} src={url_src(`/img/logo-min.png`)}/></Button>
                    {more_info && <Button onClick={() => handleShow('more_info')} variant={'link'} xs
                                          className={'text-muted'}><i
                        className={'fa fa-info-circle fa-2x'}></i></Button>}
                    {photos && <Button onClick={() => handleShow('photos')} variant={'link'} xs
                                       className={'text-muted'}><i className={'fa fa-camera fa-2x'}></i></Button>}
                    {videos && <Button onClick={() => handleShow('videos')} variant={'link'} xs
                                       className={'text-muted'}><i
                        className={'fa fa-video-camera fa-2x'}></i></Button>}
                </ButtonGroup>
            </div>

            {photos && <Modal dialogClassName={'fab-modal'} show={show === 'photos'} onHide={handleClose}>
                <Modal.Header>
                    <Container>
                        <Row className="justify-content-center text-center pt-3">
                            <Col xs={4} md={{span:1}} className={'text-right'}><i className={'fa fa-camera fa-2x'}></i></Col>
                            <Col xs={4} md={2}>
                                <h3 className={'text-uppercase'}>Fotos</h3>
                            </Col>
                            <Col xs={4} md={{span:1}} className={'text-left'}>
                                <button onClick={handleClose} className={'btn bg-brand-dark text-white btn-sm rounded-circle'}>X</button>
                            </Col>
                        </Row>
                    </Container>
                </Modal.Header>
                <Modal.Body>
                    <Row className={'content-multimedia'}>
                        {photos.map(p => <Col xs={12} md={6} className={'mb-3'}><img
                                className="d-block w-100 rounded"
                                src={p.image}
                                alt={p.title}
                        /></Col>)}
                    </Row>
                </Modal.Body>
            </Modal>}
            {videos && <Modal dialogClassName={'fab-modal'} show={show === 'videos'} onHide={handleClose}>
                <Modal.Header>
                    <Container>
                        <Row className="justify-content-center text-center pt-3">
                            <Col xs={4} md={{span:1}} className={'text-right'}><i className={'fa fa-video-camera fa-2x'}></i></Col>
                            <Col xs={4} md={2}>
                                <h3 className={'text-uppercase'}>Videos</h3>
                            </Col>
                            <Col xs={4} md={{span:1}} className={'text-left'}>
                                <button onClick={handleClose} className={'btn bg-brand-dark text-white btn-sm rounded-circle'}>X</button>
                            </Col>
                        </Row>
                    </Container>
                </Modal.Header>
                <Modal.Body>
                    <Row className={'content-multimedia'}>
                        {videos.map(v =><Col xs={12} md={6}>
                            <div class="embed-responsive embed-responsive-4by3 rounded mb-3"
                                 dangerouslySetInnerHTML={{__html: v.video_html}}>
                            </div>
                        </Col>)}
                    </Row>
                </Modal.Body>
            </Modal>}
            {more_info && <Modal dialogClassName={'fab-modal'} show={show === 'more_info'}
                                 onHide={handleClose}>
                <Modal.Header>
                    <Container>
                        <Row className="justify-content-center text-center pt-3">
                            <Col xs={4} md={{span:1}} className={'text-right'}><i className={'fa fa-info-circle fa-2x'}></i></Col>
                            <Col xs={4} md={2}>
                                <h3 className={'text-uppercase'}>Info</h3>
                            </Col>
                            <Col xs={4} md={{span:1}} className={'text-left'}>
                                <button onClick={handleClose} className={'btn bg-brand-dark text-white btn-sm rounded-circle'}>X</button>
                            </Col>
                        </Row>
                    </Container>
                </Modal.Header>
                <Modal.Body>
                    <div className={'content-multimedia'} dangerouslySetInnerHTML={{__html: more_info}}></div>
                </Modal.Body>
            </Modal>}
        </>
    );
}

const mapStateToProps = state => ({
    product: getPackageSelector(state),
})
export default connect(mapStateToProps)(FloatButtonAction)