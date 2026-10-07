import { settings } from '../settings'

const base = settings.BASE_PATH.replace(/\/+$/, '')

// Never handled by Vue Router
const reserved = /^\/(wp-admin|wp-content|wp-includes|wp-json|wp-login\.php|feed)(\/|$)|\.[a-z0-9]{2,5}$/i

const origins = () => {
    const list = [window.location.origin]

    try {
        list.push(new URL(settings.HOME_URL, window.location.origin).origin)
    } catch {
        // invalid home URL
    }

    return list
}

// WordPress URL -> router path, null if external
export const toRoutePath = (href) => {
    let url

    try {
        url = new URL(href, window.location.href)
    } catch {
        return null
    }

    if (!origins().includes(url.origin)) return null
    if (url.pathname !== base && !url.pathname.startsWith(`${base}/`)) return null

    const path = url.pathname.slice(base.length) || '/'

    if (reserved.test(path)) return null

    return path + url.search + url.hash
}

// Makes internal links inside v-html content use Vue Router
export const onContentClick = (event, router) => {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return

    const link = event.target.closest?.('a[href]')

    if (!link || (link.target && link.target !== '_self') || link.hasAttribute('download')) return

    const href = link.getAttribute('href')

    if (href.startsWith('#')) return

    const path = toRoutePath(href)

    if (path === null) return

    event.preventDefault()
    router.push(path)
}
