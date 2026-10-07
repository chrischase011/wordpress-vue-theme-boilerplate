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

// Use pinia and router
app.use(pinia)
app.use(router)

// Mount the app
app.mount('#app')
