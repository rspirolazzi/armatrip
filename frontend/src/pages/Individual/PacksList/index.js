import PropTypes from 'prop-types'
import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators, compose} from "redux"
import {getPackageGroupSelectedSelector, getPackageGroupsSelector} from "../../../store/package/selectors"
import {getSelectedStore} from "../../../store/store/selectors"
import withPageTitle from "../../../layouts/withPageTitle";
import GridPackages from "../../../components/GridPackages";
import withMenuRouter from "../../../layouts/withMenuRouter";
import {_setParamsHeader} from "../../../store/core";
import _ from "lodash";
import {getPackages} from "../../../store/package/operations"


const IndividualPacksList = ({
                                 packages_grouped,
                                 groupSelected,
                                 store,
                                 route,
                                 _setParamsHeader,
                                 getPackages
                             }) => {
    useEffect(() => {
        if (!packages_grouped.length || packages_grouped[groupSelected.code]) {
            if (store) {
                getPackages(store.code)
            }
        }
    }, [])

    const getImagesFromPackages = packages_grouped => {
        let images = []
        for (let pack of packages_grouped) {
            images = images.concat({img: pack.media.background_image, title: pack.name})
        }
        return _.unionBy(images.filter(i => i.img), (e) => e.img)
    }
    useEffect(() => {
        if (packages_grouped[groupSelected.code]) {
            _setParamsHeader({
                images: getImagesFromPackages(packages_grouped[groupSelected.code]),
                title: groupSelected.name,
                icon: 'fa-map-marker',
            })
        }
    }, [packages_grouped && packages_grouped[groupSelected.code]])

    return <div className={"text-center"}>
        <h1 className={"black text-primary text-uppercase font-weight-bold"}>Packs</h1>
        <div className={'mx-3'}>
            <GridPackages packages={packages_grouped[groupSelected.code] || []} route={route}/>
        </div>
    </div>
}

IndividualPacksList.propTypes = {
    packages_grouped: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired
}

IndividualPacksList.defaultProps = {
    packages_grouped: []
}
const mapStateToProps = state => ({
    packages_grouped: getPackageGroupsSelector(state),
    groupSelected: getPackageGroupSelectedSelector(state),
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
)(IndividualPacksList)
