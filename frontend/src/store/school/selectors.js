import {createSelector} from 'reselect';

const schoolsStateSelector = state => state.school.all
const searchingStateSelector = state => state.school.isPending === true

export const getSchoolsSelector = createSelector(
    [schoolsStateSelector],
    (schools) => {
        try {
            return schools
        } catch (error) {
            return []
        }
    }
)
export const getSchoolsSearchingSelector = createSelector(
    [searchingStateSelector],
    (searching) => {
        try {
            return searching
        } catch (error) {
            return false
        }
    }
)