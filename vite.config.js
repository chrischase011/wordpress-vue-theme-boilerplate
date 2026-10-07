import fs from 'node:fs'
import { fileURLToPath } from 'node:url'
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import FullReload from 'vite-plugin-full-reload'

import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import tailwindcss from '@tailwindcss/vite'

const root = fileURLToPath(new URL('.', import.meta.url))
const hotFile = fileURLToPath(new URL('.vite-hot', import.meta.url))

// Local WordPress hosts allowed to load from the dev server
const localOrigins = /^https?:\/\/(?:[^/:]+\.)?(?:localhost|local|loc|test|ddev\.site|lndo\.site|127\.0\.0\.1|\[::1\])(?::\d+)?$/

// Tells WordPress the dev server is running (see functions/vite.php)
function wordpressHotFile() {
  return {
    name: 'wordpress-hot-file',
    apply: 'serve',
    configureServer(server) {
      const remove = () => fs.rmSync(hotFile, { force: true })

      server.httpServer?.once('listening', () => {
        fs.writeFileSync(hotFile, server.config.server.origin)
        server.config.logger.info('\n  WordPress now loads the app from this dev server. Open your WordPress site URL, not the one below.')
      })
      server.httpServer?.once('close', remove)

      process.once('exit', remove)
      for (const signal of ['SIGINT', 'SIGTERM', 'SIGHUP']) {
        process.once(signal, () => process.exit())
      }
    },
  }
}

export default defineConfig(({ mode }) => {
  // Optional overrides in .env.local
  const env = loadEnv(mode, root, 'VITE_')
  const host = env.VITE_DEV_HOST || 'localhost'
  const port = Number(env.VITE_DEV_PORT) || 5173
  const origin = `http://${host}:${port}`

  return {
    base: './', // Works whatever the theme folder is called
    publicDir: false, // Put static files in src/assets
    resolve: {
      alias: { '@': fileURLToPath(new URL('./src', import.meta.url)) },
    },
    plugins: [
      vue(),
      FullReload(['*.php', 'functions/**/*.php']), // Reload the browser on PHP file changes
      AutoImport({
        imports: [
          'vue',
          'vue-router',
          'pinia'
        ],
        dirs: ['src/stores/**', 'src/composables'], // Auto-import stores and composables
        dts: false
      }),
      Components({
        dirs: ['src/components'], // Auto-import components from this directory
        extensions: ['vue'], // Auto-import files with .vue extension
        deep: true,
        dts: false
      }),
      tailwindcss(),
      wordpressHotFile(),
    ],
    server: {
      host,
      port,
      strictPort: true,
      origin,
      cors: {
        origin: env.VITE_WP_ORIGIN ? [localOrigins, env.VITE_WP_ORIGIN] : localOrigins,
      },
    },
    build: {
      outDir: 'dist', // Adjust based on where you want to output assets
      emptyOutDir: true,
      manifest: 'manifest.json', // Read by functions/vite.php to find the hashed files
      rollupOptions: {
        input: 'src/main.js',
      },
    },
  }
})
