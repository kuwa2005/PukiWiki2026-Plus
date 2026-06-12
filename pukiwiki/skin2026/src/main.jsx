import React from 'react'
import { createRoot } from 'react-dom/client'
import { flushSync } from 'react-dom'
import App from './App.jsx'
import './styles/app.css'

function readConfig () {
  const el = document.getElementById('s26-config')
  if (!el) return {}
  try {
    return JSON.parse(el.textContent || '{}')
  } catch {
    return {}
  }
}

const rootEl = document.getElementById('skin2026-root')
if (rootEl) {
  const config = readConfig()
  const root = createRoot(rootEl)
  flushSync(() => {
    root.render(<App config={config} />)
  })
  document.documentElement.classList.remove('s26-boot')
  document.documentElement.classList.add('s26-ready')
}
