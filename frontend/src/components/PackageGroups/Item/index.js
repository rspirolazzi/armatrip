import React from 'react'

const Item = ({_package, selectPackage, styles}) => {
    return <li>
        <button className={'btn btn-primary text-white border-0 rounded-0 mb-2 py-4'} to={'/register'}
                onClick={() => selectPackage(_package)}>{_package.name} {_package.order}</button>
    </li>
}
export default Item