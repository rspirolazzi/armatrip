import React from "react"
import {Redirect, Route, Switch} from "react-router-dom"
import routes from './_.routing'
import {connect} from 'react-redux'
import {isSelectedStore} from "../../store/store/selectors"
import {isUserIsLoggedInSelector, isUserRegisteredSelector} from "../../store/user/selectors"
import {compose} from "redux"
import withNotification from "../withNotification"
import withTracker from "../withTracker"
import {configurationStateSelector} from "../../store/configuration/selectors"
import withBodyClass from "../withBodyClass";

// eslint-disable-next-line
const Content = ({match, validation}) => <div className={'w-100'}>
    <>
        <Switch>
            <Route
                path={match.path}
                render={_ =>
                    routes.map((route, index) => (
                            <Route
                                key={index}
                                path={route.path}
                                exact={route.exact}
                                render={props => {
                                    if (route.authorize(validation)) {
                                        return <route.component {...props} routes={route.routes}/>
                                    } else {
                                        return <Redirect to={route.notAuthorizeRedirectTo || '/'}/>
                                    }
                                }}
                            />
                        )
                    )
                }
            />
        </Switch>
    </>
</div>
const mapStateToProps = (state) => {
    return {
        validation: {
            storeIsSelected: isSelectedStore(state),
            userIsRegistered: isUserRegisteredSelector(state),
            userIsLoggedIn: isUserIsLoggedInSelector(state),
            configuration: configurationStateSelector(state),
        }
    }
}
export default compose(
    withBodyClass,
    withTracker,
    withNotification,
    connect(mapStateToProps)
)(Content)
