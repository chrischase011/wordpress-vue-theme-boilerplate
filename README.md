# Headless Vue + WordPress Boilerplate
This boilerplate allows you to use Vue as the front-end for your WordPress theme in a headless architecture. WordPress handles the backend and REST API, while Vue powers the user interface using modern frontend tooling like Vite.

## 🧠 Key Features
- 🌟 Use Vue 3 with Vite for a fast and modern development experience

- ⚡ Hot Module Replacement inside WordPress: save a `.vue` file and see it instantly, no rebuild

- 🔌 One-line REST API routes in PHP and one-line calls in Vue (`useApi`, `useWp`)

- 🧭 WordPress pages, posts and menus work out of the box with Vue Router

- 🗂 Modular folder structure for components, pages, APIs, and stores

- ⚙️ Supports Vue Router and Pinia (Vue Store)

- 🎨 Style with Tailwind CSS, standard CSS or your preferred CSS preprocessor

## 📋 Requirements
- WordPress 6.7+ and PHP 8.1+
- Node.js 20+
- Pretty permalinks (Settings > Permalinks, anything except "Plain")

## 📁 Folder Structure
```bash
themes/
└── vue-boilerplate/
    ├── functions/               # WordPress PHP functions
    │   ├── api.php              # Your REST API routes
    │   ├── api-helpers.php      # vue_theme_api_get(), vue_theme_api_post(), ...
    │   ├── vite.php             # Loads the Vue app (dev server or build)
    │   └── theme-settings.php   # Theme supports and menu locations
    ├── src/                     # Vue application root
    │   ├── api/                 # API client (index.js) and JS modules for API calls
    │   │   └── wp/
    │   ├── assets/              # Static assets like images and SVGs
    │   ├── components/          # Vue components (e.g., layout)
    │   ├── composables/         # useApi and useWp
    │   ├── page/                # Vue page views
    │   ├── router/              # Vue Router setup
    │   ├── stores/              # Pinia stores
    │   │   └── wp/
    │   ├── utils/               # HTML sanitizer and link helpers
    │   ├── App.vue              # Root Vue component
    │   ├── main.js              # Vue app entry point
    │   ├── settings.js          # Values passed from WordPress
    │   └── style.css            # Global styles
    ├── dist/                    # Production build (created by npm run build)
    ├── *.php                    # WordPress theme files (header.php, footer.php, etc.)
    ├── style.css                # Theme metadata
    └── vite.config.js           # Configuration file for Vite, a fast frontend build tool.
```
## 🚀 Getting Started
1. Download and put the files inside your `themes` folder, then activate the theme.
[Download Latest Release](https://github.com/chrischase011/wordpress-vue-theme-boilerplate/releases)

2. Install Dependencies
```bash
npm install
```
3. Run the Development Server
```bash
npm run dev
```

4. Open **your WordPress site URL** (e.g. `http://mysite.local`), not the Vite URL.

While `npm run dev` is running, WordPress loads the app from the Vite dev server. Vue and CSS changes show up instantly, and PHP changes reload the page. Stop the dev server and WordPress goes back to the production build.

> Make sure WordPress is running locally (e.g., with LocalWP or Laragon) and that the theme is activated.

## 📦 Build for Production
```bash
npm run build
```

This will compile and optimize your Vue app into `dist/`. Upload the theme with the `dist` folder, you don't need `node_modules` or `src` on the server.

## 🔌 Working with the API
### Add a route (PHP)
Add it at the bottom of `functions/api.php`. Routes live under `/wp-json/vue-theme/v1/`.

```php
vue_theme_api_get('/books', function () {
    return get_posts(['post_type' => 'book']);
});

// URL parameter
vue_theme_api_get('/books/{id}', function ($request) {
    $book = get_post((int) $request['id']);

    if (!$book) {
        return new WP_Error('not_found', 'Book not found.', ['status' => 404]);
    }

    return $book;
});

// JSON body
vue_theme_api_post('/contact', function ($request) {
    $email = sanitize_email($request['email']);

    return ['sent' => true];
});

// Logged in users only, or users with a capability
vue_theme_api_post('/notes', 'my_callback', ['permission' => true]);
vue_theme_api_delete('/notes/{id}', 'my_callback', ['permission' => 'edit_posts']);
```

### Use it (Vue)
In a component, `useApi` and `useWp` are auto-imported and give you reactive state:

```vue
<script setup>
const { data: books, loading, error } = useApi('books')

// WordPress core API: posts, pages, categories, media, your custom post types...
const page = ref(1)
const { data: posts, totalPages } = useWp('posts', () => ({ per_page: 6, page: page.value }))
</script>

<template>
    <p v-if="loading">Loading...</p>
    <p v-else-if="error">{{ error }}</p>
    <ul v-else>
        <li v-for="book in books" :key="book.ID">{{ book.post_title }}</li>
    </ul>
</template>
```

Anywhere else (stores, event handlers), use the clients:

```js
import { api, wp } from '@/api' // or a relative path like '../api'

const books = await api.get('books')
const result = await api.post('contact', { email, message })
const posts = await wp.get('posts', { per_page: 5, search: 'vue' })
const { data, total, totalPages } = await wp.paginate('posts', { page: 2 })
```

Failed requests throw an `ApiError` with `message`, `status` and `code`. The nonce for logged in users is sent automatically.

## 🧭 Routing
- Add your own routes in `src/router/index.js`.
- Any other URL is looked up in WordPress: `src/page/Content.vue` shows the page or post with that slug, or a "Page not found" message.
- Menus assigned to **Primary Menu** and **Footer Menu** (Appearance > Menus) are rendered by `Header.vue` and `Footer.vue`.

## 🛠 Integration Tips
- Site title, logo and menus are available without a request: `useSiteStore()`

- Pass your own PHP data to Vue with the `vue_theme_app_config` filter, then read it from `window.wpVueTheme`

- Always use `sanitizeHtml()` from `src/utils/html.js` before rendering WordPress content with `v-html`

- Import images from `src/assets` so they get the right URL after the build

- Set `show_in_rest` to `true` on your custom post types to load them with `useWp('your-post-type')`

## ❓ Troubleshooting
- **"The Vue app is not built yet"**: run `npm install` and `npm run build`, or start `npm run dev`.
- **Changes don't show up**: check that `npm run dev` is still running and that you opened the WordPress URL.
- **Port 5173 is already in use**: create `.env.local` with `VITE_DEV_PORT=5174`.
- **Local domain is not `localhost`, `*.local`, `*.loc` or `*.test`**: add `VITE_WP_ORIGIN=http://your-domain` to `.env.local`.
- **WordPress runs in Docker**: PHP can't see the dev server on your machine, so add `add_filter('vue_theme_check_dev_server', '__return_false');` to `functions.php` while developing.
- **Vue routes return a server 404**: enable pretty permalinks in Settings > Permalinks.

## ⬆️ Upgrading from 1.x
See [CHANGELOG.md](CHANGELOG.md) for the breaking changes.

# License
See [MIT License](LICENSE)

Prepared by: [Christopher Robin Chase](https://github.com/chrischase011)
