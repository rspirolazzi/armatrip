import React, {Component} from 'react';
import GoogleAnalytics from 'react-ga';
import {_Utils} from "../store/core/_.utils";


const CONFIG = _Utils()
const TRACKING_ID = CONFIG.API.GOOGLE_ANALYTICS

// Sin ID no se carga Google Analytics. En tests, react-ga no inserta su <script>.
if (TRACKING_ID) {
    GoogleAnalytics.initialize(TRACKING_ID, {testMode: process.env.NODE_ENV === 'test'});
}

const withTracker = (WrappedComponent, options = {}) => {
    const trackPage = page => {
        if (!TRACKING_ID) {
            return;
        }
        GoogleAnalytics.set({
            page,
            ...options,
        });
        GoogleAnalytics.pageview(page);
    };

    const HOC = class extends Component {
        componentDidMount() {
            const page = this.props.location.pathname;
            trackPage(page);
        }

        componentWillReceiveProps(nextProps) {
            const currentPage = this.props.location.pathname;
            const nextPage = nextProps.location.pathname;

            if (currentPage !== nextPage) {
                trackPage(nextPage);
            }
        }

        render() {
            return <WrappedComponent {...this.props} />;
        }
    };

    return HOC;
};

export default withTracker;