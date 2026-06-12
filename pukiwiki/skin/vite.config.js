import { resolve } from 'node:path'
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    cssCodeSplit: false,
    lib: {
      entry: resolve(__dirname, 'src/main.jsx'),
      name: 'SkinApp',
      formats: ['iife'],
      fileName: () => 'skin-app.js',
    },
    rollupOptions: {
      output: {
        assetFileNames: 'skin-app.[ext]',
        inlineDynamicImports: true,
      },
    },
  },
})
