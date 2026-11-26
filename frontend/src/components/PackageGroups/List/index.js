import React, {useState} from 'react'
import {Button} from 'react-bootstrap'
import {Link} from "react-router-dom"
import {bindActionCreators} from "redux"
import {_setPackage} from "../../../store/package"
import {connect} from "react-redux"

const List = ({
                  packages_grouped,
                  _setPackage
              }) => {
    const [selected, setSelected] = useState('')
    return <ul className={'list-packages list-unstyled'}>
        {Object.keys(packages_grouped).map(group_key => {
            const group = packages_grouped[group_key][0].package_group;
            const packages = packages_grouped[group_key];
            const isSelected = selected === group_key

            const style = {}
            const style_child = {}

            if (group.styles.backgroundColor) {
                style.backgroundColor = group.styles.backgroundColor
            }
            if (group.styles.color) {
                style.color = group.styles.color
            }
            if (group.styles.style_child && group.styles.style_child.backgroundColor) {
                style_child.backgroundColor = group.styles.style_child.backgroundColor
            }
            if (group.styles.style_child && group.styles.style_child.color) {
                style_child.color = group.styles.style_child.color
            }

            return <li key={group_key} className={'mb-2'}>
                <Button block variant="primary" className={'border-0 rounded-0 py-3 text-uppercase'}
                        onClick={() => setSelected(group_key)} style={style}>{group.name}
                    <div className={'pull-right'}> {isSelected ? <i className={'fa fa-caret-down'}></i> :
                        <i className={'fa fa-caret-right'}></i>}</div>
                </Button>
                {isSelected && <ul className={'list-sub-packages list-unstyled mt-2'}>
                    {packages.map(_package => <li key={_package.code} className={'mb-2'}>
                        <Link className={'btn btn-block btn-secondary border-0 rounded-0 py-3 text-uppercase'}
                              to={'/register'} style={style_child} onClick={()=>_setPackage(_package)}>{_package.name}</Link>
                    </li>)}
                </ul>}
            </li>
        })}
    </ul>
}


const mapDispatchToProps = dispatch => bindActionCreators({
    _setPackage: _setPackage,
}, dispatch)

export default connect(
    null,
    mapDispatchToProps
)(List)
