import React, {useEffect} from "react"
import {ThemeProvider} from "react-bootstrap"
import Content from "./Content"
import withAlert from "../withAlert"
import {configurationStateSelector} from "../../store/configuration/selectors";
import {bindActionCreators, compose} from "redux";
import {connect} from "react-redux";
import {selectStore} from "../../store/store/operations";
import Header from "./Header";
import Footer from "./Footer";
import {_setParamsHeader} from "../../store/core";
import './IndividualTourism.scss'

export const LAYOUT_NAME = 'individualtourism'
const IndividualTourism = (props) => {

    useEffect(() => {
        if (props.configuration.individual_tourism && props.configuration.individual_tourism.code) {
            props._setSelectStore(props.configuration.individual_tourism)
        }
    }, [props.configuration.individual_tourism]);

    useEffect(() => {
        _setParamsHeader({})
    }, [])

    return <ThemeProvider prefixes={{}}>
        <Header {...props} layout_name={LAYOUT_NAME}/>
        <Content {...props} layout_name={LAYOUT_NAME}/>
        {/*<AddToHomeScreen appId={'armatrip_home_screen'} layout_name={LAYOUT_NAME}/>*/}
        <Footer {...props} layout_name={LAYOUT_NAME}/>
    </ThemeProvider>
}

const mapStateToProps = (state) => ({
    configuration: configurationStateSelector(state),
});
const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    _setSelectStore: selectStore,
    _setParamsHeader: _setParamsHeader,
}, dispatch)

export default compose(
    connect(
        mapStateToProps,
        mapDispatchToProps
    ),
    withAlert
)(IndividualTourism)