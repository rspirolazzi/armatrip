import Social from "../Social";
import {Image} from "react-bootstrap";
import logo_with_bg from "../img/logo_with_bg.png";
import logo from "../img/logo.png";
import HeaderCarousel from "../HeaderCarousel";
import HeaderTitle from "../HeaderTitle";
import {getIsHeaderClassicStateSelector} from "../../../../store/core/selectors";
import {compose} from "redux";
import {connect} from "react-redux";
import React from "react";

const HeaderMobile = ({
                          is_classic = false
                      }) => <>
    <div className={'d-none d-sm-flex ml-3 mt-3'}>
        <div className={'mr-auto'}>
            <Social/>
        </div>
    </div>
    {is_classic ?
        <div className={'header '}>
            <div className={'d-flex'}>
                <div className={'mr-auto  d-sm-none'}>
                    <Social/>
                </div>
                <div className={'ml-auto'}>
                    <Image className={'logo'} width={170} fluid src={logo_with_bg}/>
                </div>
            </div>
        </div>
        : <div className={'header position-relative'}>
            <div className={'position-absolute w-100'} style={{zIndex: 9999}}>
                <div className={'d-flex'}>
                    <div className={'mr-auto d-sm-none ml-3 mt-3'}>
                        <Social/>
                    </div>
                    <div className={'ml-auto'}>
                        <Image className={'logo'} width={170} fluid src={logo}/>
                    </div>
                </div>
            </div>
            <div className={'pt-5 carousel-title-container'}>
                <HeaderCarousel/>
                <HeaderTitle/>
            </div>
        </div>}
</>

const mapStateToProps = state => ({
    is_classic: getIsHeaderClassicStateSelector(state),
})

export default compose(
    connect(mapStateToProps, null)
)(HeaderMobile)
