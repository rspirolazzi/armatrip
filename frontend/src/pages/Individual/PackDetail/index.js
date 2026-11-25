import PropTypes from 'prop-types'
import React from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {getPackageDetail} from "../../../store/package/operations"
import {
    getPackageProductsSelectedSelector,
    getPackageSelector,
    getPricePackageSelector
} from "../../../store/package/selectors"
import {getSelectedStore} from "../../../store/store/selectors"
import {userSelector} from "../../../store/user/selectors"
import withMenuRouter from "../../../layouts/withMenuRouter";
import PackageBanner from "../../../components/PackageBanner";
import IndividualProductDetail from "../../../components/Individual/IndividualProductDetail";

const IndividualPackDetail = ({
                                  package_detail,
                                  route,
                              }) => {


    return <div className={"text-center"}>

        <div className={'mt-4'}>
            <PackageBanner product={package_detail}/>
        </div>
        <IndividualProductDetail {...package_detail} route={route}/>

    </div>
}

IndividualPackDetail.propTypes = {
    package_detail: PropTypes.object.isRequired,
}

IndividualPackDetail.defaultProps = {
    package_detail: {}
}
const mapStateToProps = state => ({
    user: userSelector(state),
    package_detail: getPackageSelector(state),
    services: getPackageProductsSelectedSelector(state),
    total: getPricePackageSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = dispatch => bindActionCreators({
    getPackageDetail: getPackageDetail,
}, dispatch)


export default compose(
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps),
)(IndividualPackDetail)
