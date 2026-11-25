import {createSelector} from 'reselect';

const storesStateSelector = state => state.store.all
const storeStateSelector = state => state.store.selected

export const getStoresSelector = createSelector(
    [storesStateSelector],
    (stores) => {
        try {
            return stores
        } catch (error) {
            return []
        }
    }
)
export const getSelectedStore = createSelector(
    [
        storeStateSelector,
    ],
    (store_selected) => {
        try {
            return store_selected
        } catch (error) {
            return []
        }
    }
)
export const isSelectedStore = createSelector(
    [
        storeStateSelector,
    ],
    (store_selected) => {
        try {
            return store_selected !== undefined && store_selected.code !== undefined
        } catch (error) {
            return false
        }
    }
)