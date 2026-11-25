import React from "react"
import {Carousel} from "react-bootstrap"
import _ from 'lodash'

const PackageBanner = ({product}) => {
    const {photos} = product
    if ((photos === undefined || photos === null || photos.length < 2)) {
        return null
    }
    return <Carousel className={'product-carousel'}>
        {_.chunk(photos, 2).map((photos, i) => <Carousel.Item key={i}>
            {photos.map((photo, j) => <img key={i + j}
                                           className="w-50"
                                           src={photo.image}
                                           alt={photo.title}
            />)}
        </Carousel.Item>)}
    </Carousel>

}
export default PackageBanner