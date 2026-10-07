import DOMPurify from 'dompurify'

// Use this on WordPress HTML before v-html
// Embeds are removed by default, allow with { ADD_TAGS: ['iframe'] }
export const sanitizeHtml = (html, config = {}) => {
    return DOMPurify.sanitize(html ?? '', { ADD_ATTR: ['target'], ...config })
}

// "Tom &amp; Jerry" -> "Tom & Jerry"
export const decodeEntities = (html) => {
    const textarea = document.createElement('textarea')
    textarea.innerHTML = DOMPurify.sanitize(html ?? '', { ALLOWED_TAGS: [] })

    return textarea.value
}
