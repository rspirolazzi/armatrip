import React, {useEffect} from 'react'
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {withRouter} from "react-router"
import {getBrands} from "../../../store/brand/operators"
import {getBrandsSelector} from "../../../store/brand/selectors"
import BrandCarousel from "../Carousel"

const Load = ({
                  _getBrands,
                  brands
              }) => {
    useEffect(() => {
        _getBrands()
    }, []);

    return <>
        <BrandCarousel brands={brands}/>
    </>
};

Load.propTypes = {};

Load.defaultProps = {};
const mapStateToProps = state => ({
    brands: getBrandsSelector(state),
});

const mapDispatchToProps = dispatch => bindActionCreators({
    _getBrands: getBrands,
}, dispatch);


export default withRouter(connect(mapStateToProps, mapDispatchToProps)(Load))
