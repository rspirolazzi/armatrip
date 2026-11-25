import React, {useEffect, useRef} from 'react'
import {url_src} from "../../store/core/_.utils";


const Home = ({history}) => {

    const homeContainer = useRef(null)

    let interval = 3

    useEffect(() => {
        const myEndFunction = (e) => {
            interval--
            console.log(e, interval)
            if (interval === 0) {
                history.push('/stores')
            }
        }
        homeContainer.current.addEventListener("webkitAnimationEnd", myEndFunction);
        homeContainer.current.addEventListener("animationend", myEndFunction);
    }, [])

    return <div className={"text-center"}>
        <div ref={homeContainer} className={"home-animation"}>
            <div id="home-1">
                <img alt={'Armatrip'} title={'Armatrip'} src={url_src("/img/load.png")}/>
                <div className="black">La libertad de elegir</div>
            </div>
            <div id="home-2" className="mx-4">
                <h1 className={"black"}>Cotizá el<br></br>
                    mejor valor<br></br>
                    para tu viaje
                </h1>
                <h1 className={"mb-3 black"}>y pagá el<br></br>
                    precio justo
                </h1>
            </div>
        </div>
    </div>
}

export default Home
