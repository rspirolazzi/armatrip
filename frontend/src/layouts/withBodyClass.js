import React, {Component} from 'react';

const withBodyClass = (WrappedComponent, options = {}) => {
    const prefix = 'page-'
    const convert = value => {
        let slug = value.replace(/\//g, '-')
        if (slug === '') {
            slug = 'home'
        } else if (slug.length > 0 && slug.startsWith('-')) {
            slug = slug.substr(1, slug.length)
        }
        return prefix + slug
    }
    const slugPage = page => {
        //console.log('withBodyClass', page)
        const slugs = page.substr(1, page.length).split('/').map(convert)
        return slugs
    }
    const addClassToBody = slugs => {

        let to_remove = []

        document.body.classList.forEach(c => {
            if (c.startsWith(prefix)) {
                to_remove.push(c)
            }
        })
        if (to_remove.length > 0) {
            to_remove.map(r => document.body.classList.remove(r))
        }
        if (slugs.length > 0) {
            slugs.map(r => document.body.classList.add(r))
        }
    };
    const addClassToTag = (tagName, slugs) => {

        const tags = document.getElementsByTagName('main')
        for(let tag of tags){
            let to_remove = []
            tag.classList.forEach(c => {
                if (c.startsWith(prefix)) {
                    to_remove.push(c)
                }
            })
            if (to_remove.length > 0) {
                to_remove.map(r => tag.classList.remove(r))
            }
            if (slugs.length > 0) {
                slugs.map(r => tag.classList.add(r))
            }
        }
    };

    const HOC = class extends Component {
        componentDidMount() {
            if (this.props.layout_name) {
                addClassToBody([`layout-${this.props.layout_name}`]);
                addClassToTag('main', [`main-${this.props.layout_name}`]);
            }
            const page = slugPage(this.props.location.pathname);
            addClassToBody(page);
            addClassToTag('main', page);
        }

        componentWillReceiveProps(nextProps) {
            const currentPage = this.props.location.pathname;
            const nextPage = nextProps.location.pathname;

            if (currentPage !== nextPage) {
                addClassToBody(slugPage(nextProps.location.pathname))
                addClassToTag('main', slugPage(nextProps.location.pathname))
            }
        }

        render() {
            return <WrappedComponent {...this.props} />;
        }
    };

    return HOC;
};

export default withBodyClass;