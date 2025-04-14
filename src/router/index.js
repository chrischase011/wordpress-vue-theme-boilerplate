import { createRouter, createWebHistory } from 'vue-router'
import Home from '../page/Home.vue'

// Define your routes here
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
]

// Dynamic route for posts
const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { left: 0, top: 0 }
        }
    }
})


router.beforeEach((to, next) => {
    const { title, description } = to.meta;
    const defaultTitle = 'Your Default Title';
    // const defaultDescription = 'Default Description';

    document.title = title !== undefined ? `${title} - ${defaultTitle}` : defaultTitle
    
})

// Add a body class specific to the route we're viewing (Please do not edit this part unless you know what you're doing)
router.afterEach((to) => {
    let body = document.querySelector('body')

    // Remove previous vue-related classes
    body.className = body.className
        .split(' ')
        .filter((cls) => !cls.startsWith('vue--page--'))
        .join(' ')

    const slug = to.params.postSlug || to.params.pageSlug || to.name
    body.classList.add('vue--page--' + slug)
})

export default router
