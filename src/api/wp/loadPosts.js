import { settings } from '../../settings';
import { useFetch } from '@vueuse/core'

export const usePosts = async () => {

    const { isFetching, error, data, } = await useFetch(`${settings.API_BASE_PATH}/posts?per_page=5`);

    return { isFetching, error, data, };
}