import React from 'react'
import Carousel from "react-multi-carousel";
import "react-multi-carousel/lib/styles.css";
//import AliceCarousel from 'react-alice-carousel';
//import "react-alice-carousel/lib/alice-carousel.css";

const BrandCarousel = ({brands, deviceType}) => {
    const responsive = {
        desktop: {
            breakpoint: {max: 3000, min: 1024},
            items: 3,
            //slidesToSlide: 1, // optional, default to 1.
        },
        tablet: {
            breakpoint: {max: 1024, min: 464},
            items: 3,
            //slidesToSlide: 1, // optional, default to 1.
        },
        mobile: {
            breakpoint: {max: 464, min: 0},
            items: 3,
            //slidesToSlide: 1, // optional, default to 1.
        },
    };

    return <Carousel
        swipeable={false}
        draggable={true}
        showDots={false}
        responsive={responsive}
        ssr={true} // means to render carousel on server-side.
        infinite={true}
        autoPlay={deviceType !== "mobile" ? true : false}
        autoPlaySpeed={2000}
        keyBoardControl={false}
        customTransition="all .5"
        transitionDuration={2000}
        containerClass="carousel-container"
        removeArrowOnDeviceType={["desktop", "tablet", "mobile"]}
        deviceType={deviceType}
        dotListClass="custom-dot-list-style"
    >
        {brands.filter(b=>b.logo_original).map(brand=><div style={{padding:'10px', display: 'inline-block',height: '100%',verticalAlign: 'middle'}} key={brand.code}><img style={{width:'100%', verticalAlign: 'middle',maxHeight: '25px',maxWidth: '160px',}} src={brand.logo_original} className="yours-custom-class"/></div>)}
    </Carousel>;
}
export default BrandCarousel;
/*
const BrandCarousel = ({brands}) => {

    const handleOnDragStart = e => e.preventDefault()

    const [state, setState]=useState({galleryItems: [1, 2, 3].map((i) => (<h2 key={i}>{i}</h2>)),})


    const responsive = {
        0: {items: 1},
        1024: {items: 2},
    }

    const onSlideChange=(e)=>{
        console.debug('Item`s position during a change: ', e.item)
        console.debug('Slide`s position during a change: ', e.slide)
    }

    const onSlideChanged=(e)=>{
        console.debug('Item`s position after changes: ', e.item)
        console.debug('Slide`s position after changes: ', e.slide)
    }
    return (
        <AliceCarousel
            items={state.galleryItems}
            responsive={responsive}
            autoPlayInterval={2000}
            autoPlayDirection="rtl"
            autoPlay={true}
            fadeOutAnimation={true}
            mouseDragEnabled={true}
            playButtonEnabled={false}
            disableAutoPlayOnAction={true}
            dotsDisabled={true}
            buttonsDisabled={true}
            onSlideChange={onSlideChange}
            onSlideChanged={onSlideChanged}
        >{brands.map(brand=><img src={brand.logo} onDragStart={handleOnDragStart} className="yours-custom-class"/>)}
        </AliceCarousel>
    )
}
*/

//export default BrandCarousel;