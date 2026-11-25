import React, {useState} from "react"
import {Button, Form, Modal} from "react-bootstrap"
import {
    EmailIcon,
    EmailShareButton,
    FacebookIcon,
    FacebookShareButton,
    TwitterIcon,
    TwitterShareButton,
    WhatsappIcon,
    WhatsappShareButton,
} from 'react-share';

const ShareButtonModal = ({
                              className,
                              children,
                              url,
                              message
                          }) => {
    const [show, setShow] = useState(false);
    const [textCopied, setTextCopied] = useState('');

    const handleClose = () => setShow(false);
    const handleShow = () => setShow(true);
    const handleCopy = (e) => {
        e.target.select();
        document.execCommand('copy');
        setTextCopied('Copiaste el vínculo con éxito')
    };

    return (
        <>
            <Button className={className} variant="primary" onClick={handleShow}>
                {children}
            </Button>

            <Modal show={show} onHide={handleClose}>
                <Modal.Header closeButton>
                    <Modal.Title>Compartir</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <div className={'mx-4'}>

                        <div className={'mb-3'}><FacebookShareButton url={url} quote={message}><FacebookIcon size={32} round={true}/> <span className={''}>Facebook</span></FacebookShareButton></div>
                        <div className={'mb-3'}><TwitterShareButton url={url} title={message}><TwitterIcon size={32} round={true}/> <span className={''}>Twitter</span></TwitterShareButton></div>
                        <div className={'mb-3'}><WhatsappShareButton url={url} title={message} separator={' | '}><WhatsappIcon size={32} round={true}/> <span className={''}>Whatsapp</span></WhatsappShareButton></div>
                        <div className={'mb-3'}><EmailShareButton url={url} subject={message} body={'Hola, acabo de compartir esta cotización con vos.'}><EmailIcon size={32} round={true}/> <span className={''}>Email</span></EmailShareButton></div>
                        <small className={'form-text text-muted'}>Haz click para copiar el vínculo</small>
                        <Form.Group>
                            <Form.Control
                                size={'sm'}
                                autoComplete={"new-password"}
                                type="_url"
                                name="_url"
                                readOnly
                                value={url}
                                onClick={handleCopy}
                                isValid={textCopied}
                            />
                            {textCopied && <Form.Control.Feedback type="valid">
                                {textCopied}
                            </Form.Control.Feedback>}
                        </Form.Group>
                    </div>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Cerrar
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    );
}

export default ShareButtonModal;