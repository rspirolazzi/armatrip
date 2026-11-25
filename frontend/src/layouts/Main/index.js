import React from "react"
import Header from "../../components/Header"
import Footer from "../../components/Footer"
import {ThemeProvider} from "react-bootstrap"
import Content from "./Content"
import withAlert from "../withAlert"
import AddToHomeScreen from "../../components/AddToHomeScreen";
import './Main.scss'

export const LAYOUT_NAME = 'main'

const Main = (props) => {
    return <ThemeProvider prefixes={{}}>
        <Header layout_name={LAYOUT_NAME}/>
        <Content {...props} layout_name={LAYOUT_NAME}/>
        <AddToHomeScreen appId={'armatrip_home_screen'} layout_name={LAYOUT_NAME}/>
        <Footer layout_name={LAYOUT_NAME}/>
    </ThemeProvider>
}
export default withAlert(Main)