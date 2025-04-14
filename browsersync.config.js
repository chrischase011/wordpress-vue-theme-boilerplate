import browserSync from 'browser-sync'

const bs = browserSync.create()

bs.init({
    proxy: 'http://upgaa.dev.com/', // Adjust to your local WP URL
    files: ['**/*.php', 'dist/**/*.js', 'dist/**/*.css'],
    notify: false,
    open: false,
})
