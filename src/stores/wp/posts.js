import { defineStore } from "pinia"

export const usePostsStore = defineStore('wp-posts', {
    state: () => ({
        posts: [],
        post: {},
        counter: 0,
    }),
    actions: {
        increment() {
            this.counter++
            console.log("Access from store")
        }
    }
})