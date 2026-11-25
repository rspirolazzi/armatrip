import {createSelector} from 'reselect';

const provincesStateSelector = state => state.province.provinces;
const provinceStateSelector = state => state.province.province;

export const getProvincesSelector = createSelector(
    [provincesStateSelector],
    (all) => {
        try {
            return all
        } catch (error) {
            return null;
        }
    }
);
export const getProvinceSelectedSelector = createSelector(
    [provinceStateSelector],
    (province) => {
        try {
            return province||{}
        } catch (error) {
            return null;
        }
    }
);