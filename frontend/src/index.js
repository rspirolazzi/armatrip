import React from 'react'
import ReactDOM from 'react-dom'
import {Provider} from 'react-redux'
import {PersistGate} from 'redux-persist/integration/react'
import createHistory from 'history/createBrowserHistory'
import configurationStore from './store/configureStore'
import App from './App'
import {Router} from 'react-router-dom'
import {withRouter} from 'react-router';
import * as serviceWorker from './serviceWorker';
//import 'bootstrap/dist/css/bootstrap.min.css';

const NonBlockApp = withRouter(App);
const history = createHistory()

console.log(`Version:${process.env.REACT_APP_NAME} ${process.env.REACT_APP_VERSION}`)

const {store, persistor} = configurationStore(history)
ReactDOM.render(
    <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
            <Router history={history}>
                <NonBlockApp/>
            </Router>
        </PersistGate>
    </Provider>,
    document.getElementById('root')
)

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: http://bit.ly/CRA-PWA
serviceWorker.register();
