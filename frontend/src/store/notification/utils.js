export const ACTION_CHECK = 'check'
export const ACTION_UN_CHECK = 'un_check'
const _ACTION_CHECK = 'select'
const _ACTION_UN_CHECK = 'unselect'

export const actionMap = {
    [_ACTION_CHECK]: ACTION_CHECK,
    [_ACTION_UN_CHECK]: ACTION_UN_CHECK,
}
export const selectActionForProduct = (notifications, product, check) => {
    let returnNotifications = []
    //console.log('Esta uri devuelve ', notifications)
    notifications.forEach((actions) => {
        if (actions.rules && actions.rules.length > 0) {
            let filter1 = []
            let filter2 = []
            let filter3 = []
            let map1 = [];


            filter1 = actions.rules.filter(actionFilter => {
                return actionFilter.product.id === product.id
            });
            //console.log('filter1:', filter1)

            filter2 = filter1.filter(actionFilter => {
                return (check === true && actionFilter.action.id === _ACTION_CHECK) || (check === false && actionFilter.action.id === _ACTION_UN_CHECK)
            });
            //console.log('filter2:', filter2)

            map1 = filter2.map((actionFilter => {
                return {
                    ...actions,
                    is_actions: undefined,
                    rules: undefined,
                    action: actionMap[actionFilter.action.id]
                }
            }));
            //console.log('map1:', map1)

            filter3 = map1.filter(r => {
                return r.action
                //return r.action !== null &&r.action !== undefined
            });
            //console.log('filter3:', filter3)
            returnNotifications = returnNotifications.concat(filter3)
            //console.log('selectActionForProduct devuelve ', returnNotifications)
        }
    })
    return returnNotifications
}