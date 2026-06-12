export const SIDEBAR_WIDTH_KEY = 'pukiwiki-skin-sidebar-width'
export const SIDEBAR_MIN = 200
export const SIDEBAR_MAX = 400
export const SIDEBAR_DEFAULT = 280

export function clampSidebarWidth (value) {
  const n = Number(value)
  if (!Number.isFinite(n)) return SIDEBAR_DEFAULT
  return Math.min(SIDEBAR_MAX, Math.max(SIDEBAR_MIN, Math.round(n)))
}

export function readSidebarWidth () {
  if (typeof window === 'undefined') return SIDEBAR_DEFAULT
  try {
    return clampSidebarWidth(localStorage.getItem(SIDEBAR_WIDTH_KEY))
  } catch {
    return SIDEBAR_DEFAULT
  }
}

export function writeSidebarWidth (width) {
  try {
    localStorage.setItem(SIDEBAR_WIDTH_KEY, String(clampSidebarWidth(width)))
  } catch {
    /* ignore quota / private mode */
  }
}
