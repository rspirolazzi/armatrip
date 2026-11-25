import React from 'react'
import {Carousel} from "react-bootstrap";
import {compose} from "redux";
import {connect} from "react-redux";
import {getImagesHeaderStateSelector} from "../../../../store/core/selectors";

const HeaderCarousel = ({images_header}) => {

    if (!images_header || images_header.length === 0) {
        return null
    }
    return <Carousel indicators={false} controls={images_header.length > 1}>
        {images_header.map(({img, title}, i) => <Carousel.Item key={i}>
            <img
                className="w-100 rounded-pill-top-left rounded-half-pill-bottom-right"
                src={img}
                alt={title}
            />
        </Carousel.Item>)}
    </Carousel>
}
const mapStateToProps = state => ({
    images_header: getImagesHeaderStateSelector(state),
})


export default compose(
    connect(mapStateToProps, null)
)(HeaderCarousel)