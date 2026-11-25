import coreService from '../../services/Core'
import {_setLogToServer} from './index'

/* Operations */
export const setLogToServer = (message,data) => _setLogToServer(coreService.postLogToServer({message,data:JSON.stringify(data)}))