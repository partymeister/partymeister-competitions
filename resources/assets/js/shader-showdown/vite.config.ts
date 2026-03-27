import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import fs from 'fs'

const laravelRoot = path.resolve(__dirname, '../../../../../..')
const hotFile = path.resolve(laravelRoot, 'public/hot-shader-showdown')

export default defineConfig({
  plugins: [
    vue(),
    {
      name: 'hot-file',
      configureServer(server) {
        fs.writeFileSync(hotFile, 'http://localhost:5178')
        server.httpServer?.on('close', () => {
          fs.rmSync(hotFile, { force: true })
        })
      },
    },
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname),
    },
  },
  publicDir: false,
  build: {
    outDir: path.resolve(laravelRoot, 'public/build/shader-showdown'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, 'main.ts'),
    },
  },
  server: {
    port: 5178,
    origin: 'http://localhost:5178',
  },
})
