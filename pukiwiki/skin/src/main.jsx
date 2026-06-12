import React from 'react'
import { createRoot } from 'react-dom/client'
import { flushSync } from 'react-dom'
import App from './App.jsx'
import './styles/app.css'

function readConfig () {
  const el = document.getElementById('skin-app-config')
  if (!el) return {}
  try {
    return JSON.parse(el.textContent || '{}')
  } catch {
    return {}
  }
}

const rootEl = document.getElementById('skin-app-root')
if (rootEl) {
  const config = readConfig()
  const root = createRoot(rootEl)
  flushSync(() => {
    root.render(<App config={config} />)
  })
  document.documentElement.classList.remove('skin-app-boot')
  document.documentElement.classList.add('skin-app-ready')
}
