import React from 'react'
import {Button, Form, InputGroup} from 'react-bootstrap'

const Newsletter = () => {
    return <Form>
        <Form.Group>
            <Form.Label className={'text-uppercase'}>No te pierdas ninguna promoción</Form.Label>
            <InputGroup>
                <Form.Control
                    as={'email'}
                />
                <InputGroup.Append>
                    <Button variant={'primary'}>Suscribirme</Button>
                </InputGroup.Append>
            </InputGroup>
        </Form.Group>
    </Form>
}

export default Newsletter