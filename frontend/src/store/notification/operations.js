import notificationService from '../../services/Notification'
import {_getNotifications} from './index'

/* Operations */
export const getNotifications = () => _getNotifications(notificationService.getNotifications())
