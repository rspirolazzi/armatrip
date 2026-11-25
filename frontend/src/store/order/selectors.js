import {createSelector} from 'reselect';

const orderDateStateSelector = state => state.order.date;
export const getOrderPublicCodeUrlStateSelector = state => {
    let public_code_url = ''
    if (state.order && state.order.order && state.order.order.public_code_url) {
        public_code_url = state.order.order.public_code_url
    }
    return public_code_url
}
export const orderHasBeenCheckoutStateSelector = state => state.order.checkout === true;
export const shareOrderStateSelector = state => state.order.order_share;

export const getOrderDateSelector = createSelector(
    [orderDateStateSelector],
    (date) => {
        try {
            return date
        } catch (error) {
            return null;
        }
    }
);