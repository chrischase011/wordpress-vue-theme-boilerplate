import browserSync from 'browser-sync'

const bs = browserSync.create()

bs.init({
    proxy: 'http://localhost:8000/', // Adjust to your local WP URL
    files: ['**/*.php', 'dist/**/*.js', 'dist/**/*.css'],
    notify: false,
    open: false,
})
