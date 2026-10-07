<script setup>
import { computed } from 'vue'
import { useSiteStore } from '../../stores/wp/site'

const site = useSiteStore()

// Used when no menu is assigned in Appearance > Menus
const fallbackMenu = [
    { id: 'home', title: 'Home', url: '/', children: [] },
    { id: 'blog', title: 'Blog', url: '/blog', children: [] },
]

const menu = computed(() => {
    const items = site.menu('primary')

    return items.length ? items : null
})
</script>

<template>
    <header class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-200 px-5 py-4 lg:px-16">
        <router-link to="/" class="flex items-center gap-3 text-lg font-bold">
            <img v-if="site.logo" :src="site.logo.url" :alt="site.logo.alt || site.title" class="h-10 w-auto" />
            <span>{{ site.title }}</span>
        </router-link>

        <nav aria-label="Primary">
            <NavMenu v-if="menu" :items="menu" />
            <ul v-else class="flex flex-wrap items-center gap-x-6 gap-y-2">
                <li v-for="item in fallbackMenu" :key="item.id">
                    <router-link :to="item.url" class="hover:underline">{{ item.title }}</router-link>
                </li>
            </ul>
        </nav>
    </header>
</template>

<style scoped>
</style>
