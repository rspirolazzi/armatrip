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
import {LAYOUT_NAME} from "./index";
import {Container} from "react-bootstrap";

// eslint-disable-next-line
const Content = ({match, validation, configuration}) => {
    return <Container as={'main'} fluid={'sm'}>
        <>
            <Switch>
                <Route
                    path={match.path}
                    render={_ =>
                        routes.map((route, index) => (
                                <Route
                                    key={index}
                                    path={`${match.path}${route.path}`}
                                    exact={route.exact}
                                    render={props => {
                                        if (route.authorize(validation)) {
                                            return <route.component {...props}
                                                                    route={route}
                                                                    layout_name={LAYOUT_NAME}
                                                                    routes={routes}
                                                                    page_title={route.title}
                                                                    base_path={configuration.individual_tourism.url}/>
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
    </Container>
}
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
