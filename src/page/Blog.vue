<script setup>
import { computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { usePostsStore } from '../stores/wp/posts'

const route = useRoute()
const store = usePostsStore()

// Page number comes from the URL: /blog?pg=2
const page = computed(() => Math.max(1, parseInt(route.query.pg, 10) || 1))

watch(page, (value) => store.fetchPosts({ page: value, perPage: 9 }), { immediate: true })
</script>

<template>
    <div class="container mx-auto my-10">
        <h1 class="mb-8 text-4xl font-bold">Blog</h1>

        <p v-if="store.loading">Loading...</p>
        <p v-else-if="store.error" class="text-red-700" role="alert">{{ store.error }}</p>
        <p v-else-if="!store.posts.length">No posts found.</p>
        <div v-else class="grid gap-6 md:grid-cols-3">
            <PostCard v-for="post in store.posts" :key="post.id" :post="post" />
        </div>

        <nav v-if="store.totalPages > 1" class="mt-10 flex items-center justify-center gap-6" aria-label="Pagination">
            <router-link v-if="page > 1" :to="{ query: { pg: page - 1 } }" class="underline">Previous</router-link>
            <span>Page {{ page }} of {{ store.totalPages }}</span>
            <router-link v-if="page < store.totalPages" :to="{ query: { pg: page + 1 } }" class="underline">Next</router-link>
        </nav>
    </div>
</template>

<style scoped>
</style>
