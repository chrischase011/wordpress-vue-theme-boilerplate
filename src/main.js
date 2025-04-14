import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import router from './router'
import { createPinia } from 'pinia'
const pinia = createPinia()


const app = createApp(App);

// Globally register Swiper components
// app.component('Navigation', Navigation)
// app.component('Pagination', Pagination)

// Use router and pinia
app.use(router)
app.use(pinia)

// Mount the app
app.mount('#app')