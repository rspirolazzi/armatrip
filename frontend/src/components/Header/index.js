import React from 'react'
import {Navbar} from "react-bootstrap"
import {url_src} from "../../store/core/_.utils";
import {FACEBOOK_URL, INSTAGRAM_URL} from "../../common/constants";
import VersionNumber from "../VersionNumber";

const Header = () => (<header className="px-4 border-bottom-line">
    <div className={'d-block d-md-none'}>
        <Navbar collapseOnSelect expand="lg" className="justify-content-center">
            <Navbar.Brand as={'a'} href={"/"}>
                <img
                    src={url_src("/img/logo.svg")}
                    height="32"
                    className="d-inline-block align-top"
                    alt="Armatrip"
                />
            </Navbar.Brand>
            <span className={'d-none'}>
                <VersionNumber/>
            </span>
        </Navbar>
    </div>
    <div className={'d-none d-md-block'}>
        <Navbar>
            <Navbar.Brand as={'a'} href={"/"}>
                <img
                    src={url_src("/img/logo.svg")}
                    height="30"
                    className="d-inline-block align-top"
                    alt="Armatrip"
                />
            </Navbar.Brand>
            <Navbar.Collapse className="justify-content-end">
                {FACEBOOK_URL && <Navbar.Text>
                    <a href={FACEBOOK_URL}
                       target={'_blank'} rel={'noopener noreferrer'} className={'social-size'}>
                    <span className="fa-stack text-brand">
                      <i className="fa fa-circle fa-stack-2x"></i>
                      <i className="fa fa-facebook fa-stack-1x fa-inverse"></i>
                    </span>
                    </a>
                </Navbar.Text>}
                {/*<Navbar.Text>
                    <a href={'#'} target={'_blank'} rel={'noopener noreferrer'} className={'social-size'}>
                    <span className="fa-stack text-info">
                      <i className="fa fa-circle fa-stack-2x"></i>
                      <i className="fa fa-twitter fa-stack-1x fa-inverse"></i>
                    </span>
                    </a>
                </Navbar.Text>*/}
                {INSTAGRAM_URL && <Navbar.Text>
                    <a href={INSTAGRAM_URL} target={'_blank'} rel={'noopener noreferrer'} className={'social-size'}>
                    <span className="fa-stack text-brand">
                      <i className="fa fa-circle fa-stack-2x"></i>
                      <i className="fa fa-instagram fa-stack-1x fa-inverse"></i>
                    </span>
                    </a>
                </Navbar.Text>}
            </Navbar.Collapse>
        </Navbar>
    </div>
</header>)
export default Header