import path from 'path'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue2'

export default defineConfig({
  plugins: [
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/sass/app.scss',
        'resources/js/app.js'
      ],
      refresh: true
    })
  ]
})
