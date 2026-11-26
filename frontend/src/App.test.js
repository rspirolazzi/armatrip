import React from 'react';
import ReactDOM from 'react-dom';
import {Provider} from 'react-redux';
import {Router, withRouter} from 'react-router-dom';
import {createMemoryHistory} from 'history';
import configureStore from './store/configureStore';
import App from './App';

// Sin red: las llamadas a la API quedan pendientes y no actualizan el estado.
jest.mock('axios', () => {
    const pending = () => new Promise(() => {});
    return {get: pending, post: pending, put: pending, delete: pending};
});

// Mismo árbol que index.js, sin PersistGate para que App se monte de inmediato.
it('renders without crashing', () => {
    const history = createMemoryHistory();
    const {store} = configureStore(history);
    const NonBlockApp = withRouter(App);
    const div = document.createElement('div');

    ReactDOM.render(
        <Provider store={store}>
            <Router history={history}>
                <NonBlockApp/>
            </Router>
        </Provider>,
        div
    );
    ReactDOM.unmountComponentAtNode(div);
});
