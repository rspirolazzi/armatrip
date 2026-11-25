import React from 'react'
import {Link} from "react-router-dom"

const Item = ({_package, selectPackage, styles}) => {
    return <Link className={'btn btn-primary text-white border-0 rounded-0 mb-2 py-4'} to={'/register'} onClick={()=>selectPackage(_package)}>{_package.name}</Link>
}
export default Item