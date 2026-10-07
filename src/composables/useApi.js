import { ref, shallowRef, toValue, watch, onScopeDispose } from 'vue'
import { api, wp } from '../api'

// Reloads when path or params change
const createUseApi = (client) => (path, params = {}, { immediate = true } = {}) => {
    const data = shallowRef(null)
    const total = ref(0)
    const totalPages = ref(0)
    const loading = ref(immediate)
    const error = ref(null)

    let controller = null

    const refresh = async () => {
        // Cancel the previous request
        controller?.abort()
        controller = new AbortController()
        const { signal } = controller

        loading.value = true
        error.value = null

        try {
            const response = await client.paginate(toValue(path), toValue(params), { signal })

            data.value = response.data
            total.value = response.total
            totalPages.value = response.totalPages
        } catch (e) {
            if (e.name === 'AbortError') return

            data.value = null
            error.value = e.message
        } finally {
            if (!signal.aborted) loading.value = false
        }
    }

    watch(() => [toValue(path), JSON.stringify(toValue(params))], refresh, { immediate })
    onScopeDispose(() => controller?.abort())

    return { data, total, totalPages, loading, error, refresh }
}

// const { data, loading, error } = useApi('hello', { name: 'Vue' })
export const useApi = createUseApi(api)

// const { data: posts, totalPages } = useWp('posts', { per_page: 5 })
export const useWp = createUseApi(wp)
