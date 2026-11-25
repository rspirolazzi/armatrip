import PropTypes from 'prop-types'
import React, {Component} from 'react'
import List from "../../components/Cart/Packages"
import {connect} from 'react-redux'
import {bindActionCreators} from "redux"
import {getPackages} from "../../store/package/operations"
import {getPackagesSelector} from "../../store/package/selectors"
import {getSelectedStore} from "../../store/store/selectors"

class Packages extends Component {

    componentWillMount() {
        if (this.props.store) {
            this.props.dispatchLoadPackages({code:this.props.store.code})
        }
    }

    render() {
        const {
            packages
        } = this.props
        return <div>
            <h4 className={'text-brand text-center'}>Seleccioná tu destino</h4>
            <List packages={packages}/>
        </div>
    }
}

Packages.propTypes = {
    packages: PropTypes.array.isRequired
}

Packages.defaultProps = {
    packages: []
}
const mapStateToProps = state => ({
    packages: getPackagesSelector(state),
    store: getSelectedStore(state),
})

const mapDispatchToProps = (dispatch, props) => bindActionCreators({
    dispatchLoadPackages: getPackages,
}, dispatch)


export default connect(mapStateToProps, mapDispatchToProps)(Packages)
