import React from 'react'
import moment from 'moment'
import {connect} from 'react-redux'
import {configurationStateSelector} from '../../store/configuration/selectors'

// "build 57 (abc1234, 27/11/2026)", o "dev" en compilaciones sin datos del CI.
export const buildLabel = (build = {}) => {
    if (!build || !build.number) {
        return 'dev'
    }
    const details = [
        build.sha,
        build.date ? moment(build.date, 'YYYY-MM-DD').format('DD/MM/YYYY') : null,
    ].filter(Boolean)
    return `build ${build.number}${details.length ? ` (${details.join(', ')})` : ''}`
}

const webBuild = {
    number: process.env.REACT_APP_BUILD_NUMBER,
    sha: process.env.REACT_APP_BUILD_SHA,
    date: process.env.REACT_APP_BUILD_DATE,
}

// Versiones desplegadas de la web y de la API (la de la API llega en GET /config).
const AppVersion = ({configuration}) => <div className={'app-version'}>
    <span>Web {process.env.REACT_APP_VERSION ? `${process.env.REACT_APP_VERSION} · ` : ''}{buildLabel(webBuild)}</span>
    {configuration && configuration.build && <>
        <span aria-hidden={'true'}> · </span>
        <span>API {buildLabel(configuration.build)}</span>
    </>}
</div>

const mapStateToProps = state => ({
    configuration: configurationStateSelector(state),
})

export default connect(mapStateToProps)(AppVersion)
