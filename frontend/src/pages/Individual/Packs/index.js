import PropTypes from 'prop-types'
import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {getPackages} from "../../../store/package/operations"
import {getPackageGroupsSelector} from "../../../store/package/selectors"
import {getSelectedStore} from "../../../store/store/selectors"
import {Container} from "react-bootstrap"
import GridPackagesGroup from "../../../components/GridPackagesGroup";
import withPageTitle from "../../../layouts/withPageTitle";
import Banners from "../../../components/Banners";
import withMenuRouter from "../../../layouts/withMenuRouter";
import {_setParamsHeader} from "../../../store/core";
import _ from "lodash";

const IndividualPacks = ({
                             packages_grouped,
                             store,
                             getPackages,
                             route,
                             _setParamsHeader,
                         }) => {


    const getImagesFromPackagesGrouped = packages_grouped => {
        let images = []
        for (let key of Object.keys(packages_grouped)) {
            images = images.concat(packages_grouped[key].map(p => {
                return {img: p.media.background_image, title: p.name}
            }))
        }
        return _.unionBy(images.filter(i => i.img), (e) => e.img)
    }

    useEffect(() => {
        if (store) {
            getPackages(store.code)
        }
    }, [store])
    useEffect(() => {
        if (Object.keys(packages_grouped).length > 0) {
            _setParamsHeader({
                images: getImagesFromPackagesGrouped(packages_grouped),
                title: 'Turismo Individual',
            })
        }
    }, [packages_grouped])

    return <div className={"text-center"}>
        <h1 className={"black text-primary text-uppercase font-weight-bold"}>Packs</h1>
        <Banners/>
        <Container className={'py-4'}>
            <GridPackagesGroup packages_grouped={packages_grouped} route={route}/>
        </Container>
    </div>
}

IndividualPacks.propTypes = {
    packages_grouped: PropTypes.array.isRequired
}

IndividualPacks.defaultProps = {
    packages_grouped: []
}
const mapStateToProps = state => ({
    packages_grouped: getPackageGroupsSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    getPackages: getPackages,
    _setParamsHeader: _setParamsHeader,
}, dispatch)


export default compose(
    withPageTitle,
    withMenuRouter,
    connect(mapStateToProps, mapDispatchToProps)
)(IndividualPacks)
