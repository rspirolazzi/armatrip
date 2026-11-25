import {createSelector} from 'reselect';

const brandsStateSelector = state => state.brand.brands;
const brandStateSelector = state => state.brand.brand;

export const getBrandsSelector = createSelector(
    [brandsStateSelector],
    (all) => {
        try {
            return all
        } catch (error) {
            return null;
        }
    }
);
export const getBrandSelectedSelector = createSelector(
    [brandStateSelector],
    (brand) => {
        try {
            return brand || {}
        } catch (error) {
            return null;
        }
    }
);