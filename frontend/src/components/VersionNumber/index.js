import React from 'react';
import moment from 'moment';
import preval from 'preval.macro';

const buildTimestamp = preval`module.exports = new Date().getTime();`;

class VersionNumber extends React.Component {
    getDateString = () => {
        const lastUpdateMoment = moment.unix(buildTimestamp / 1000);
        return lastUpdateMoment.format('DD.MM.YYYY HH:mm:ss');
    };

    render () {
        return (
            <div>
                {process.env.REACT_APP_NAME} {process.env.REACT_APP_VERSION}
                {'.'}
                {buildTimestamp}
                {' '}
                {'('}
                {this.getDateString()}
                {')'}
            </div>
        );
    }
}

VersionNumber.propTypes = {};

VersionNumber.defaultProps = {};

export default VersionNumber;