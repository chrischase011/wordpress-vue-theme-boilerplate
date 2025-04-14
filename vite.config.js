import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import browsersync from 'browser-sync-webpack-plugin'
import FullReload from 'vite-plugin-full-reload'

import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    vue(),
    FullReload(['**/*.php', '**/*.vue', '**/*.js']), // Reload on PHP file changes,
    AutoImport({
      imports: [
        'vue',
        'pinia'
      ],
      dirs: ['src/stores'], // Auto-import all stores from this folder
      dts: false
    }),
    Components({
      dirs: ['src/components'], // Auto-import components from this directory
      extensions: ['vue'], // Auto-import files with .vue extension
      deep: true,
      dts: false
    }),
    tailwindcss(),
  ],
  server: {
    proxy: {
      '/': {
        target: 'http://example.com/', // Target proxy URL
        changeOrigin: true,
        secure: false,
      },
    },
  },
  build: {
    outDir: 'dist', // Adjust based on where you want to output assets
  },
})
