// Injected by WordPress (see functions/vite.php)
const wp = window.wpVueTheme || {}

export const settings = {
    REST_URL: wp.restUrl || '/wp-json/', // WordPress REST API root (Do not change this unless you know what you are doing)
    NONCE: wp.nonce || null, // Only set for logged in users
    BASE_PATH: wp.basePath || '/', // Subdirectory WordPress is installed in
    HOME_URL: wp.homeUrl || '/',
    THEME_URL: wp.themeUrl || '',
    IS_DEV: Boolean(wp.isDev),
    SITE: wp.site || {},
    MENUS: wp.menus || {},
}
