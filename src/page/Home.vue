<script setup>
import viteLogo from '@/assets/vite.svg'
import vueLogo from '@/assets/vue.svg'

const site = useSiteStore()

// WordPress posts
const { data: posts, loading, error } = useWp('posts', { per_page: 3, _embed: 'wp:featuredmedia' })

// Custom route from functions/api.php
const { data: hello } = useApi('hello', { name: 'Vue' })
</script>

<template>
    <div class="container flex flex-col items-center gap-10 mx-auto my-10 text-center">
        <div class="flex">
            <a href="https://vite.dev" target="_blank" rel="noopener">
                <img :src="viteLogo" class="logo" alt="Vite logo" />
            </a>
            <a href="https://vuejs.org/" target="_blank" rel="noopener">
                <img :src="vueLogo" class="logo vue" alt="Vue logo" />
            </a>
        </div>

        <div>
            <h1 class="text-4xl font-bold">{{ site.title }}</h1>
            <p v-if="site.description" class="mt-2 text-gray-600">{{ site.description }}</p>
            <p class="mt-4 text-gray-500">Edit <code>src/page/Home.vue</code> and save to see it update instantly.</p>
            <p v-if="hello" class="mt-2 text-sm text-gray-500">{{ hello.message }}</p>
        </div>

        <section class="w-full">
            <h2 class="mb-5 text-2xl font-bold">Latest posts</h2>

            <p v-if="loading">Loading...</p>
            <p v-else-if="error" class="text-red-700" role="alert">{{ error }}</p>
            <p v-else-if="!posts?.length">No posts yet.</p>
            <div v-else class="grid gap-6 md:grid-cols-3">
                <PostCard v-for="post in posts" :key="post.id" :post="post" />
            </div>

            <router-link to="/blog" class="mt-6 inline-block underline">View all posts</router-link>
        </section>
    </div>
</template>


<style lang="scss" scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;

  &:hover {
    filter: drop-shadow(0 0 2em #646cffaa);
  }

  &.vue:hover {
    filter: drop-shadow(0 0 2em #42b883aa);
  }
}
</style>
