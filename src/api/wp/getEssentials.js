import { api } from '../index'

// Already loaded in the site store, use these to refresh

export const getSiteInfo = (options = {}) => {

    return api.get('site', {}, options);
}

// location: 'primary' or 'footer'
export const getMenu = (location, options = {}) => {

    return api.get(`menus/${location}`, {}, options);
}
