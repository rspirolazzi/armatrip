import {createSelector} from 'reselect';

const userIsLoggedStateSelector = state => state.user.isLoggedIn;
const userStateSelector = state => state.user.user;
const userStoreStateSelector = state => state.user;
export const jwtStateSelector = state => state.user.jwt;
export const isValidStateSelector = state => state.user.isValid;

export const isLoggedSelector = createSelector(
    [userIsLoggedStateSelector],
    (isLoggedIn) => {
        try {
            return isLoggedIn
        } catch (error) {
            return false;
        }
    }
)

export const userSelector = createSelector(
    [userStateSelector],
    (user) => {
        try {
            return user || {}
        } catch (error) {
            return {};
        }
    }
)

export const isUserRegisteredSelector = createSelector(
    [userStateSelector],
    (user) => {
        try {
            return user !== undefined && user.email !== undefined
        } catch (error) {
            return {};
        }
    }
)
export const isUserIsLoggedInSelector = createSelector(
    [userStateSelector, jwtStateSelector],
    (user, jwt) => {
        try {
            return user !== undefined && jwt !== undefined
        } catch (error) {
            return {};
        }
    }
)
export const isPhoneSentSelector = createSelector(
    [userStoreStateSelector],
    (user) => {
        try {
            return user !== undefined && user.sendPhone === true
        } catch (error) {
            return {};
        }
    }
)