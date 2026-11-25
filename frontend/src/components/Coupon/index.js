import React from "react";

export default <></>
//TODO revisar porque no esta funcionando
/*
import PropTypes from "prop-types";
import { bindActionCreators } from "redux";
import connect from "react-redux/es/connect/connect";
import { apply, remove } from "../../store/coupon/operations"
import { addedCouponSelector, maxLengthCouponSelector, isVisibleCouponSelector, couponStateSelector } from '../../store/coupon/selectors'
import { configurationStateSelector } from '../../store/configuration/selectors'

import {InputGroup,Form,FormControl,Button} from "react-bootstrap"

const Coupon = ({
    coupon,
    applied,
    handleApply,
    handleRemove,
    maxLength,
    isVisible,
    handleCallbackAfterAddCoupon = null
}) => {

    const [show, setShow] = useState(false);
    const [checking, setChecking] = useState(false);
    const [code, setCode] = useState('');
    const [message, setMessage] = useState('');

    useEffect(() => {

        /!*const componentWillReceiveProps = (newProps) => {
            if (newProps.isVisible !== props.isVisible) {
                setState({ isVisible: newProps.isVisible })
            }
            if (newProps.applied !== props.applied) {
                setState({ applied: newProps.applied })
            }
            if (newProps.code !== props.code) {
                setState({ code: newProps.code })
            }
        }*!/


    });


    const onSubmit = (e) => {
        e.preventDefault()
        submit()
    }
    const submit = () => {
        if (code.length > 0) {
            setCodeToApply(code)
        } else {
            setMessage(`Debes ingresar un código valido de hasta ${maxLength} letras o números`)
        }
    }
    const onChange = (e) => {
        const { value } = e.target
        setCode(value)
        if (value.length === maxLength) {
            setCodeToApply(value)
        }
    }
    const onRemove = (e) => {
        e.preventDefault()
        handleRemove(coupon.code)
            .then(resp => {
                if (handleCallbackAfterAddCoupon) {
                    handleCallbackAfterAddCoupon(resp)
                    setShow(false);
                    setCode('');
                }
            })
    }
    const setCodeToApply = code => {
        setChecking(true)
        handleApply(code)
            .then(resp => {
                if (handleCallbackAfterAddCoupon) {
                    handleCallbackAfterAddCoupon(resp)
                }
            })
            .catch(() => setCode(''))
            .finally(() => setChecking(false))
    }

    if (isVisible !== true) {
        return null
    }
    if (applied) {
        return <div className={'coupon message'}>
            Cupón <b>{coupon.name}</b> aplicado <a title={'Sacar descuento'} href={'#'} onClick={onRemove} className={'text-danger'}><i className={'fa fa-times'}></i></a>
        </div>
    }
    if(!show){
        return <a onClick={(e)=>{
            e.preventDefault();
            setShow(true);
        }} className={"book font-size-14"} href="#">Tengo un cupón con beneficio</a>
    }
    return <Form onSubmit={onSubmit} >
        <p className={"book font-size-14"}>Podés ingresar el cupón con beneficio</p>
        <InputGroup>
            <FormControl
                aria-label="Código"
                name={'code'}
                onChange={onChange}
                className={'form-control'}
                disabled={checking}
                value={code}
                placeholder={'Código'}
                maxLength={maxLength}
                autoComplete={"new-password"}
            />
            <InputGroup.Append>
                <Button variant="outline-secondary" disabled={checking} className={'button negro'} type="submit">
                    Usar {checking &&
                        <i className={'fa fa-spinner fa-spin'}></i>}
                </Button>
            </InputGroup.Append>
        </InputGroup>
        {message && <p className={"book font-size-12 " + (applied ? "text-success" : "text-danger")}>{message}</p>}
    </Form>
}

Coupon.propTypes = {
    /!*    maxLength: PropTypes.number.required,
        isVisible: PropTypes.bool.required,
        applied: PropTypes.bool,
        code: PropTypes.string,*!/
};

Coupon.defaultProps = {
    maxLength: 8,
    isVisible: false,
    applied: false,
    code: '',
};



const mapStateToProps = state => ({
    applied: addedCouponSelector(state),
    configuration: configurationStateSelector(state),
    coupon: couponStateSelector(state),
    maxLength: maxLengthCouponSelector(state),
    isVisible: isVisibleCouponSelector(state),
})
/!*{
    const couponConf = state.configuration.coupon
    const couponApplied = state.webCart.coupon
    const {checking} = state.coupon
    let isVisible = true, maxLength = 8
    if (couponConf != null) {
        isVisible = couponConf.isVisible
        maxLength = couponConf.length
    }
    const {code, reward} = couponApplied || {}

    return {
        isVisible,
        maxLength,
        checking,
        code,
        reward,
        applied: code !== undefined,
    }
}*!/
const matDispatchToProps = dispatch => bindActionCreators({
    handleApply: apply,
    handleRemove: remove,
}, dispatch)
export default connect(mapStateToProps, matDispatchToProps)(Coupon)*/
