import {createSelector} from 'reselect';

const coreStateSelector = state => state.core;
const headerStateSelector = state => state.core.header;
const messageStateSelector = state => state.core.message;
const titleStateSelector = state => state.core.title || 'Ocurrio un error';
const errorStateSelector = state => state.core.isError || true;
const showStateSelector = state => state.core.show;

const loadingStateSelector = state => state.core.loading

export const messageSelector = createSelector(
    [messageStateSelector],
    (message) => {
        try {
            return message
        } catch (error) {
            return '';
        }
    }
)
export const modalObjectSelector = createSelector(
    [
        messageStateSelector,
        titleStateSelector,
        errorStateSelector,
        showStateSelector,
    ],
    (message, title, isError, show) => {
        try {
            return {
                message, title, error: isError ? 'danger' : 'info', show
            }
        } catch (error) {
            return {show: false};
        }
    }
)
export const isShowModalSelector = createSelector(
    [showStateSelector],
    (show) => {
        try {
            return show
        } catch (error) {
            return false;
        }
    }
)
export const isLoadingSelector = createSelector(
    [loadingStateSelector],
    (show) => {
        try {
            return show
        } catch (error) {
            return false;
        }
    }
)
export const getImagesHeaderStateSelector = createSelector(
    [headerStateSelector],
    (header) => {
        try {
            return header.images || []
        } catch (error) {
            return [];
        }
    }
)
export const getIsHeaderClassicStateSelector = createSelector(
    [headerStateSelector],
    (header) => {
        try {
            return header.is_classic || false
        } catch (error) {
            return false;
        }
    }
)
export const getTitleHeaderStateSelector = createSelector(
    [headerStateSelector],
    (header) => {
        try {
            return header.title || ''
        } catch (error) {
            return [];
        }
    }
)
export const getIconHeaderStateSelector = createSelector(
    [headerStateSelector],
    (header) => {
        try {
            return header.icon || ''
        } catch (error) {
            return [];
        }
    }
)