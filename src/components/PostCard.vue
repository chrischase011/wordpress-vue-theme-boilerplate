<script setup>
import { computed } from 'vue'
import { sanitizeHtml } from '../utils/html'
import { toRoutePath } from '../utils/links'
import { useSiteStore } from '../stores/wp/site'

const props = defineProps({
    post: { type: Object, required: true },
})

const site = useSiteStore()

const to = computed(() => toRoutePath(props.post.link) || `/${props.post.slug}`)
const image = computed(() => {
    const media = props.post._embedded?.['wp:featuredmedia']?.[0]

    return media?.source_url ? { src: media.source_url, alt: media.alt_text || '' } : null
})
const date = computed(() => new Date(props.post.date).toLocaleDateString(site.language, { dateStyle: 'medium' }))
</script>

<template>
    <article class="flex flex-col overflow-hidden rounded-lg border border-gray-200 text-left">
        <router-link v-if="image" :to="to" tabindex="-1" aria-hidden="true">
            <img :src="image.src" :alt="image.alt" loading="lazy" class="aspect-video w-full object-cover" />
        </router-link>

        <div class="flex flex-col gap-2 p-5">
            <time :datetime="post.date" class="text-sm text-gray-500">{{ date }}</time>
            <h2 class="text-xl font-bold">
                <router-link :to="to" class="hover:underline" v-html="sanitizeHtml(post.title.rendered)"></router-link>
            </h2>
            <div class="text-gray-700" v-html="sanitizeHtml(post.excerpt?.rendered)"></div>
        </div>
    </article>
</template>

<style scoped>
</style>
