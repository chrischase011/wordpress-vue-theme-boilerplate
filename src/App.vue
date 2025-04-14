<script setup>
import { watch } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const isHome = ref(router.currentRoute.value.path === '/')

watch(router.currentRoute, (to, from) => {
  isHome.value = to.path === '/'
})

</script>

<template>
  <Header />
    <main class="relative px-0 lg:px-16 mx-auto py-5 gap-16" v-cloak>
      <router-view></router-view>
    </main>
  <Footer />
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
