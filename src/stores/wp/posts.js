import { defineStore } from "pinia"
import { getPosts } from "../../api/wp/loadPosts"

let controller = null

export const usePostsStore = defineStore('wp-posts', {
    state: () => ({
        posts: [],
        page: 1,
        total: 0,
        totalPages: 0,
        loading: false,
        error: null,
    }),
    actions: {
        async fetchPosts({ page = 1, perPage = 10, ...params } = {}) {
            // Cancel the previous request
            controller?.abort()
            controller = new AbortController()
            const { signal } = controller

            this.loading = true
            this.error = null

            try {
                const { data, total, totalPages } = await getPosts({ page, perPage, ...params }, { signal })

                this.posts = data
                this.page = page
                this.total = total
                this.totalPages = totalPages
            } catch (error) {
                if (error.name === 'AbortError') return

                this.posts = []
                this.error = error.message
            } finally {
                if (!signal.aborted) this.loading = false
            }
        },
    }
})
