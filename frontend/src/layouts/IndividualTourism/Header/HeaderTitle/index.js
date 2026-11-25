import React from 'react'
import {getIconHeaderStateSelector, getTitleHeaderStateSelector} from "../../../../store/core/selectors";
import {compose} from "redux";
import {connect} from "react-redux";

const HeaderTitle = ({
                         title,
                         icon = null,
                     }) => {
    if (!title) {
        return null
    }
    if (icon) {
        return <div className={'position-relative header-title with-icon ml-2'}>
            <div className={'position-absolute rounded-circle bg-info text-center'}></div>
            <div className={'position-absolute rounded-circle bg-primary text-center'}>
                <div className={'mt-2'}>
                    <i className={`fa ${icon} fa-3x text-white`}></i>
                </div>
            </div>
            <div className={'ml-4 pl-4'}>
                <div className={'shadow-lg rounded-pill-bottom-right bg-info text-uppercase text-white pl-4 pr-4 py-1'}>
                    <h5 className={'text-uppercase text-white my-1'}>{title}</h5>
                </div>
            </div>
        </div>
    } else {
        return <div className={'header-title'}>
            <div className={'shadow-lg rounded-pill-bottom-right bg-info text-uppercase text-white px-4 py-1'}>
                <h5 className={'text-uppercase text-white'}>{title}</h5>
            </div>
        </div>
    }
}

const mapStateToProps = state => ({
    title: getTitleHeaderStateSelector(state),
    icon: getIconHeaderStateSelector(state),
})


export default compose(
    connect(mapStateToProps, null)
)(HeaderTitle)