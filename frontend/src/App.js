import React, {useEffect, useState} from 'react'
import {connect} from "react-redux"
import {isLoggedSelector} from "./store/user/selectors"
import {BrowserRouter, Route, Switch} from "react-router-dom"
import MainLayout from './layouts/Main'
import {bindActionCreators, compose} from "redux"
import {getNotifications} from "./store/notification/operations"
import {getConfigurations} from "./store/configuration/operations"
import {_Utils} from "./store/core/_.utils";
import IndividualTourismLayout from "./layouts/IndividualTourism";
//import './layouts/Main/Main.scss'
import {configurationStateSelector} from "./store/configuration/selectors";

const CONFIG = _Utils()


const App = ({_getNotifications, _getConfigurations, configuration}) => {
    /*
    State para cargar las notificaciones de la URI
     */
    const [notificationsLoaded, setNotificationsLoaded] = useState(false)

    useEffect(() => {
        if (!notificationsLoaded) {
            _getNotifications()
            setNotificationsLoaded(true)
        }
    })

    const [configurationsLoaded, setConfigurationsLoaded] = useState(false)

    useEffect(() => {
        if (!configurationsLoaded) {
            _getConfigurations()
            setConfigurationsLoaded(true)
        }
    })
    return <>
        <BrowserRouter basename={CONFIG.API.BASE_PATH}>
            <Switch>
                {configuration.individual_tourism && configuration.individual_tourism.url ? <Route
                    path={configuration.individual_tourism.url}
                    render={(props) => <IndividualTourismLayout {...props} />
                    }/> : null}
                <Route
                    path='*'
                    render={(props) => <MainLayout {...props} />
                    }/>
            </Switch>
        </BrowserRouter>
    </>
}
const mapStateToProps = (state) => ({
    isLogged: isLoggedSelector(state),
    configuration: configurationStateSelector(state),
});
const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _getNotifications: getNotifications,
    _getConfigurations: getConfigurations,
}, dispatch)

export default compose(
    connect(
        mapStateToProps,
        mapDispatchToProps
    ),
    //withMenuRouter,
)(App);