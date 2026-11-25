import React, {Component} from 'react';

const withPageTitle = (WrappedComponent, options = {args: [], join: ' ', divider: ' : '}) => {
    const HOC = class extends Component {
        componentDidMount() {
            document.title = process.env.REACT_APP_NAME
            if (this.props.page_title) {
                document.title += `${options.divider}${this.props.page_title} ${options.args.join(options.join)}`
            }
        }

        render() {
            return <WrappedComponent {...this.props} />;
        }
    };

    return HOC;
};

export default withPageTitle;