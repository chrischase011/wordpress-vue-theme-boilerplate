import { createRouter, createWebHistory } from 'vue-router'
import Home from '../page/Home.vue'
import { settings } from '../settings'

// Define your routes here
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/blog',
        name: 'blog',
        component: () => import('../page/Blog.vue'),
        meta: { title: 'Blog' },
    },
    // WordPress pages and posts (Keep this route last)
    {
        path: '/:pathMatch(.*)*',
        name: 'content',
        component: () => import('../page/Content.vue'),
    },
]

const router = createRouter({
    history: createWebHistory(settings.BASE_PATH),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else if (to.hash) {
            return { el: to.hash }
        } else {
            return { left: 0, top: 0 }
        }
    }
})

export const setDocumentTitle = (title) => {
    const defaultTitle = settings.SITE.title || 'Your Default Title';

    document.title = title ? `${title} - ${defaultTitle}` : defaultTitle
}

router.afterEach((to) => {
    setDocumentTitle(to.meta.title)
})

// Add a body class specific to the route we're viewing (Please do not edit this part unless you know what you're doing)
router.afterEach((to) => {
    let body = document.querySelector('body')

    // Remove previous vue-related classes
    body.className = body.className
        .split(' ')
        .filter((cls) => !cls.startsWith('vue--page--'))
        .join(' ')

    const slug = [].concat(to.params.pathMatch || []).filter(Boolean).pop() || to.name
    body.classList.add('vue--page--' + String(slug).replace(/\s+/g, '-'))
})

export default router
