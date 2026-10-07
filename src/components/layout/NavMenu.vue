<script setup>
import { computed } from 'vue'
import { toRoutePath } from '../../utils/links'

const props = defineProps({
    items: { type: Array, default: () => [] },
})

// Internal URLs become router links, everything else stays a normal link
const withRoutes = (items) => items.map((item) => ({
    ...item,
    to: item.target === '_blank' ? null : toRoutePath(item.url),
    children: withRoutes(item.children || []),
}))

const links = computed(() => withRoutes(props.items))
</script>

<template>
    <ul class="flex flex-wrap items-center gap-x-6 gap-y-2">
        <li v-for="item in links" :key="item.id" class="group relative" :class="item.classes">
            <router-link v-if="item.to" :to="item.to" class="hover:underline">{{ item.title }}</router-link>
            <a v-else :href="item.url" :target="item.target || null" :rel="item.target === '_blank' ? 'noopener' : null" class="hover:underline">{{ item.title }}</a>

            <ul v-if="item.children.length" class="absolute left-0 top-full z-10 hidden min-w-40 rounded border border-gray-200 bg-white py-2 shadow group-hover:block group-focus-within:block">
                <li v-for="child in item.children" :key="child.id" :class="child.classes">
                    <router-link v-if="child.to" :to="child.to" class="block px-4 py-1 hover:underline">{{ child.title }}</router-link>
                    <a v-else :href="child.url" :target="child.target || null" :rel="child.target === '_blank' ? 'noopener' : null" class="block px-4 py-1 hover:underline">{{ child.title }}</a>
                </li>
            </ul>
        </li>
    </ul>
</template>

<style scoped>
</style>
