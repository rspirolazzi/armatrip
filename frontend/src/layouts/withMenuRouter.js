import React, {Component} from 'react';
import {LAYOUT_NAME as LAYOUT_NAME_INDIVIDUAL_TOURISM} from "./IndividualTourism";
import {menu as menuIndividualTourism} from "./IndividualTourism/_.routing";
import {_Utils} from "../store/core/_.utils";

const withMenuRouter = (WrappedComponent) => {
    const HOC = class extends Component {
        render() {
            const _route = (source, key, params = null, absolute = false, base = '') => {
                const regex = /\:([A-Za-z0-9]*)/gm;
                const str = `${source[key].path}`;
                let m, url = str, i = 0;

                while ((m = regex.exec(str)) !== null) {
                    if (m.index === regex.lastIndex) {
                        regex.lastIndex++;
                    }
                    url = url.replace(m[0], params[i]);
                    i++

                }
                if (base) {
                    url = `${base}${url}`.replace('//', '/')
                }
                if (absolute) {
                    url = `${_Utils().API.ABSOLUTE_URL}${url}`
                }
                return url;
            }
            let route
            const {history, match} = this.props
            switch (this.props.layout_name) {
                case LAYOUT_NAME_INDIVIDUAL_TOURISM:
                    route = (key, args, absolute = false) => {
                        //console.log('Route to:', key, args, menuIndividualTourism)
                        const url = _route(menuIndividualTourism, key, args, absolute, this.props.base_path || '/')
                        history.push(url)
                    }
                    break;
                default:
                    break;
            }
            return <WrappedComponent {...this.props} route={route}/>;
        }
    };

    return HOC;
};

export default withMenuRouter