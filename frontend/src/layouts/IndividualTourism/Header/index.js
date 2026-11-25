import React from 'react'
import {isMobile} from "../../../common/utils";
import HeaderMobile from './Mobile'
import HeaderDesktop from './Desktop'

const Header = (props) => isMobile() ? <HeaderMobile {...props}/> : <HeaderDesktop {...props}/>
export default Header