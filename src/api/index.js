import { settings } from '../settings'

export class ApiError extends Error {
    constructor(message, { status = 0, code = null, data = null } = {}) {
        super(message)
        this.name = 'ApiError'
        this.status = status
        this.code = code
        this.data = data
    }
}

// Supports pretty (/wp-json/) and plain (?rest_route=) permalinks
export const apiUrl = (path, params = {}) => {
    const url = new URL(settings.REST_URL, window.location.origin)
    const route = String(path).replace(/^\/+/, '')

    if (url.searchParams.has('rest_route')) {
        url.searchParams.set('rest_route', `/${route}`)
    } else {
        url.pathname = `${url.pathname.replace(/\/+$/, '')}/${route}`
    }

    for (const [key, value] of Object.entries(params)) {
        if (value === undefined || value === null || value === '') continue
        url.searchParams.set(key, Array.isArray(value) ? value.join(',') : value)
    }

    return url
}

// Returns { data, total, totalPages }, throws ApiError
export const wpFetch = async (path, { params, method = 'GET', body, headers = {}, signal } = {}) => {
    const init = {
        method,
        signal,
        credentials: 'same-origin',
        headers: { Accept: 'application/json', ...headers },
    }

    if (settings.NONCE) {
        init.headers['X-WP-Nonce'] = settings.NONCE
    }

    if (body !== undefined) {
        init.headers['Content-Type'] = 'application/json'
        init.body = JSON.stringify(body)
    }

    let response

    try {
        response = await fetch(apiUrl(path, params), init)
    } catch (error) {
        if (error.name === 'AbortError') throw error
        throw new ApiError('Could not reach the server. Please check your connection.')
    }

    const data = response.status === 204 ? null : await response.json().catch(() => undefined)

    if (!response.ok) {
        throw new ApiError(data?.message || `Request failed (${response.status})`, {
            status: response.status,
            code: data?.code,
            data,
        })
    }

    if (data === undefined) {
        throw new ApiError('The server sent an invalid response.', { status: response.status })
    }

    return {
        data,
        total: Number(response.headers.get('X-WP-Total')) || 0,
        totalPages: Number(response.headers.get('X-WP-TotalPages')) || 0,
    }
}

export const createApi = (namespace) => {
    const route = (path) => `${namespace}/${String(path).replace(/^\/+/, '')}`

    return {
        get: async (path, params, options = {}) => (await wpFetch(route(path), { ...options, params })).data,
        // Same as get but returns { data, total, totalPages }
        paginate: (path, params, options = {}) => wpFetch(route(path), { ...options, params }),
        post: async (path, body, options = {}) => (await wpFetch(route(path), { ...options, method: 'POST', body })).data,
        put: async (path, body, options = {}) => (await wpFetch(route(path), { ...options, method: 'PUT', body })).data,
        patch: async (path, body, options = {}) => (await wpFetch(route(path), { ...options, method: 'PATCH', body })).data,
        delete: async (path, params, options = {}) => (await wpFetch(route(path), { ...options, method: 'DELETE', params })).data,
        url: (path, params) => apiUrl(route(path), params),
    }
}

// wp.get('posts', { per_page: 5 }) -> /wp-json/wp/v2/posts?per_page=5
export const wp = createApi('wp/v2')

// api.get('hello') -> /wp-json/vue-theme/v1/hello (add routes in functions/api.php)
export const api = createApi('vue-theme/v1')
