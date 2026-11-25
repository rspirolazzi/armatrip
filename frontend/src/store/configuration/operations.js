import configurationService from '../../services/Configuration'

import {_getConfigurations} from './index'

export const getConfigurations = ()=> _getConfigurations(configurationService.config({}))