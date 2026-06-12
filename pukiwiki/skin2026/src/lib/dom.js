const STORAGE_KEY = 'pukiwiki-skin2026-theme'

export function readTheme () {
  try {
    const saved = localStorage.getItem(STORAGE_KEY)
    if (saved === 'light' || saved === 'dark') return saved
  } catch { /* ignore */ }
  if (typeof window !== 'undefined' && window.matchMedia?.('(prefers-color-scheme: dark)').matches) {
    return 'dark'
  }
  return 'light'
}

export function writeTheme (theme) {
  document.documentElement.setAttribute('data-theme', theme)
  try {
    localStorage.setItem(STORAGE_KEY, theme)
  } catch { /* ignore */ }
}

export function adoptNode (id, ref) {
  const el = document.getElementById(id)
  if (!el || !ref.current) return
  el.removeAttribute('hidden')
  el.hidden = false
  el.classList.add('s26-adopted')
  ref.current.appendChild(el)
}
