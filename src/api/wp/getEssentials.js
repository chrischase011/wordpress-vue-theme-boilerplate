import { settings } from '../../settings';
import { useFetch } from '@vueuse/core'

export const useGetLogo = async () => {

    const { isFetching, error, data, } = await useFetch(`${settings.API_BASE_PATH}/logo`);

    return { isFetching, error, data, };
}

export const useGetSiteTitle = async () => {
    
    const { isFetching, error, data, } = await useFetch(`${settings.API_BASE_PATH}/site-title`);

    return { isFetching, error, data, };
}