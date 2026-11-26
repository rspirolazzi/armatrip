import React from 'react'
import UserMobileInput from "../../components/UserMobileInput"
import UserMobileActivation from "../../components/UserMobileActivation"
import {userSelector} from "../../store/user/selectors"
import {connect} from "react-redux"

const Activation = ({user}) => <div>
        <h4 className={"text-brand"}>Hola {user.name}!</h4>
        <p>Necesitamos validar tu teléfono para poder contactarte.</p>
    <UserMobileInput/>
    <UserMobileActivation/>
</div>
const mapStateToProps = (state, props) => {
    return {
        user: userSelector(state)
    }
}
export default connect(mapStateToProps)(Activation)