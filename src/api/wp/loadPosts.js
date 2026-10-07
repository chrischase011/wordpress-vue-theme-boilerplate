import { wp } from '../index'

// Extra params go to the REST API (search, categories, ...)
export const getPosts = ({ page = 1, perPage = 10, ...params } = {}, options = {}) => {

    return wp.paginate('posts', { page, per_page: perPage, _embed: 'wp:featuredmedia,author', ...params }, options);
}

export const getPostsBySlug = (slug, options = {}) => {

    return wp.get('posts', { slug, _embed: 'wp:featuredmedia,author' }, options);
}
