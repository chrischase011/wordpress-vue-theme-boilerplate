<script setup>
import { ref, computed, watch, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getPagesBySlug } from '../api/wp/loadPages'
import { getPostsBySlug } from '../api/wp/loadPosts'
import { setDocumentTitle } from '../router'
import { sanitizeHtml, decodeEntities } from '../utils/html'
import { onContentClick, toRoutePath } from '../utils/links'
import { useSiteStore } from '../stores/wp/site'

const route = useRoute()
const router = useRouter()
const site = useSiteStore()

const entry = ref(null)
const loading = ref(true)
const error = ref(null)

let controller = null

const trim = (path) => String(path).replace(/^\/+|\/+$/g, '')

// Slugs can repeat, prefer the one matching the URL
const pick = (entries) => {
    return entries.find((item) => trim(toRoutePath(item.link)?.split(/[?#]/)[0] ?? '') === trim(route.path)) || entries[0] || null
}

const load = async () => {
    controller?.abort()
    controller = new AbortController()
    const { signal } = controller

    loading.value = true
    error.value = null
    entry.value = null

    const slug = [].concat(route.params.pathMatch || []).filter(Boolean).pop()

    try {
        let found = null

        if (slug) {
            found = pick(await getPagesBySlug(slug, { signal })) || pick(await getPostsBySlug(slug, { signal }))
        }

        entry.value = found
        setDocumentTitle(found ? decodeEntities(found.title.rendered) : 'Page not found')
    } catch (e) {
        if (e.name === 'AbortError') return

        error.value = e.message
    } finally {
        if (!signal.aborted) loading.value = false
    }
}

watch(() => route.path, load, { immediate: true })
onBeforeUnmount(() => controller?.abort())

const image = computed(() => {
    const media = entry.value?._embedded?.['wp:featuredmedia']?.[0]

    return media?.source_url ? { src: media.source_url, alt: media.alt_text || '' } : null
})
const author = computed(() => entry.value?._embedded?.author?.[0]?.name || '')
const date = computed(() => new Date(entry.value.date).toLocaleDateString(site.language, { dateStyle: 'long' }))
</script>

<template>
    <div class="container mx-auto my-10 max-w-3xl">
        <p v-if="loading">Loading...</p>

        <div v-else-if="error" role="alert">
            <p class="text-red-700">{{ error }}</p>
            <button type="button" class="mt-4 underline" @click="load">Try again</button>
        </div>

        <article v-else-if="entry">
            <h1 class="text-4xl font-bold" v-html="sanitizeHtml(entry.title.rendered)"></h1>
            <p v-if="entry.type === 'post'" class="mt-2 text-sm text-gray-500">
                <time :datetime="entry.date">{{ date }}</time>
                <span v-if="author"> &middot; {{ author }}</span>
            </p>
            <img v-if="image" :src="image.src" :alt="image.alt" class="mt-6 w-full rounded-lg" />

            <p v-if="entry.content.protected" class="mt-6">This content is password protected.</p>
            <div v-else class="entry-content clearfix mt-6" v-html="sanitizeHtml(entry.content.rendered)" @click="onContentClick($event, router)"></div>
        </article>

        <div v-else class="text-center">
            <h1 class="text-4xl font-bold">Page not found</h1>
            <p class="mt-4 text-gray-600">The page you are looking for does not exist or has been moved.</p>
            <router-link to="/" class="mt-6 inline-block underline">Back to home</router-link>
        </div>
    </div>
</template>

<style scoped>
</style>
