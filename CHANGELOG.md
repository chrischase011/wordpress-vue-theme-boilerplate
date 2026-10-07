# Changelog

## 2.0.0

### Added
- Hot Module Replacement inside WordPress with `npm run dev`. No proxy URL to configure, just open your WordPress site.
- One-line REST API routes in PHP: `vue_theme_api_get()`, `vue_theme_api_post()`, `vue_theme_api_put()`, `vue_theme_api_delete()`.
- `api` and `wp` clients and the `useApi()` / `useWp()` composables for calling the REST API from Vue.
- Blog page with pagination, and a catch-all view that renders WordPress pages and posts or a "Page not found" message.
- Primary and Footer menu locations, rendered in Vue with router links.
- `window.wpVueTheme` config passed from PHP (REST URL, nonce, site info, menus), extendable with the `vue_theme_app_config` filter.
- Site store (`useSiteStore`) with the site title, tagline, logo and menus.
- `sanitizeHtml()` helper for `v-html` and Vue Router navigation for internal links inside post content.
- Admin notice when the theme has not been built yet.
- `vue-theme/v1/site` and `vue-theme/v1/menus/{location}` endpoints.

### Fixed
- WordPress installed in a subdirectory (e.g. `http://localhost/mysite`) now works.
- Images and lazy-loaded routes now load correctly in production (assets are loaded as ES modules through the Vite manifest).
- WordPress no longer redirects unknown Vue routes to a similar looking post.
- PHP warning in the logo endpoint when the logo attachment is missing.
- `.wp-block-heading` styles were never applied because of wrong selectors.
- Stores in `src/stores/wp` were not auto-imported.
- Global styles from the demo component changed the layout of the whole site.

### Changed
- `npm run start` now starts the Vite dev server (same as `npm run dev`). `npm run watch` rebuilds on change.
- The build entry is `src/main.js`. `index.html` and the `public` folder are gone, put static files in `src/assets`.
- The sample custom post type is now available in the REST API.
- Updated dependencies, `npm audit` reports 0 vulnerabilities.

### Breaking changes
- BrowserSync is removed (`browsersync.config.js`, `npm run browsersync`, the snippet in `footer.php`).
- PHP functions are prefixed with `vue_theme_`: `get_site_title()`, `rest_get_custom_logo()`, `load_vite_assets()`, `load_custom_theme_settings()`, `load_custom_scripts()` and `init_custom_post_types()` were renamed.
- Script and style handles changed from `theme-js` / `theme-css` to `vue-theme-app` / `vue-theme-app-{n}`.
- `settings.API_BASE_PATH` is replaced by the `api` / `wp` clients in `src/api/index.js`.
- `usePosts()`, `useGetLogo()` and `useGetSiteTitle()` are replaced by `getPosts()`, `useWp('posts')` and `useSiteStore()`.
- The posts store no longer has `post`, `counter` and `increment()`.
- `HelloWorld.vue` is removed.
- Node.js 20 or newer is required.

The old `wp/v2/site-title` and `wp/v2/logo` endpoints still work but are deprecated.
