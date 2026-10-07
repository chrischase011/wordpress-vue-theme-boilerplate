<script setup>
import { useSiteStore } from '../../stores/wp/site'

const site = useSiteStore()
const year = new Date().getFullYear()
</script>

<template>
    <footer class="flex flex-wrap items-center justify-between gap-4 border-t border-gray-200 px-5 py-5 lg:px-16">
        <p>&copy; {{ year }} {{ site.title }}</p>

        <nav v-if="site.menu('footer').length" aria-label="Footer">
            <NavMenu :items="site.menu('footer')" />
        </nav>
    </footer>
</template>


<style scoped>
</style>
