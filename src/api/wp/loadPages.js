import { wp } from '../index'

export const getPagesBySlug = (slug, options = {}) => {

    return wp.get('pages', { slug, _embed: 'wp:featuredmedia' }, options);
}
