import { useCallback, useEffect, useLayoutEffect, useMemo, useRef, useState } from 'react'
import CommandPalette from './components/CommandPalette.jsx'
import Fab from './components/Fab.jsx'
import MobileNav from './components/MobileNav.jsx'
import Sidebar from './components/Sidebar.jsx'
import TopBar from './components/TopBar.jsx'
import { adoptNode, readTheme, writeTheme } from './lib/dom.js'

export default function App ({ config }) {
  const [sidebarOpen, setSidebarOpen] = useState(() =>
    typeof window !== 'undefined' && window.matchMedia('(min-width: 1024px)').matches
  )
  const [paletteOpen, setPaletteOpen] = useState(false)
  const [theme, setTheme] = useState(readTheme)

  const menuRef = useRef(null)
  const bodyRef = useRef(null)
  const rightbarRef = useRef(null)
  const noteRef = useRef(null)
  const attachRef = useRef(null)
  const footerRef = useRef(null)
  const toolbarRef = useRef(null)
  const titleRef = useRef(null)
  const topicpathRef = useRef(null)

  useLayoutEffect(() => {
    adoptNode('menubar', menuRef)
    adoptNode('body', bodyRef)
    adoptNode('rightbar', rightbarRef)
    adoptNode('note', noteRef)
    adoptNode('attach', attachRef)
    adoptNode('footer', footerRef)
    adoptNode('toolbar', toolbarRef)
    adoptNode('skin-app-page-title', titleRef)
    adoptNode('skin-app-topicpath', topicpathRef)

    const ssr = document.getElementById('skin-app-ssr')
    if (ssr) ssr.remove()
  }, [])

  useEffect(() => {
    writeTheme(theme)
  }, [theme])

  useEffect(() => {
    const mq = window.matchMedia('(min-width: 1024px)')
    const onChange = (e) => setSidebarOpen(e.matches)
    mq.addEventListener('change', onChange)
    return () => mq.removeEventListener('change', onChange)
  }, [])

  const toggleTheme = useCallback(() => {
    setTheme((t) => (t === 'dark' ? 'light' : 'dark'))
  }, [])

  const navItems = useMemo(() => {
    const items = []
    for (const group of config.nav || []) {
      for (const item of group.items || []) {
        items.push({ ...item, group: group.label })
      }
    }
    return items
  }, [config.nav])

  useEffect(() => {
    const onKey = (e) => {
      if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault()
        setPaletteOpen(true)
      }
      if (e.key === 'Escape') setPaletteOpen(false)
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [])

  const editHref = config.links?.edit || ''
  const searchHref = config.links?.search || ''
  const showToolbars = config.showToolbars !== false
  const showFab = showToolbars && config.rw && config.isPage && config.isRead && editHref

  return (
    <div className={`s26-app${showToolbars ? '' : ' s26-app--no-toolbars'}`} data-theme={theme}>
      <div className="s26-bg" aria-hidden="true">
        <div className="s26-bg-orb s26-bg-orb--1" />
        <div className="s26-bg-orb s26-bg-orb--2" />
        <div className="s26-bg-grid" />
      </div>

      <Sidebar
        open={sidebarOpen}
        onToggle={() => setSidebarOpen((v) => !v)}
        config={config}
        menuRef={menuRef}
      />

      <div className={`s26-main${sidebarOpen ? ' s26-main--sidebar-open' : ''}`}>
        <TopBar
          config={config}
          theme={theme}
          showToolbar={showToolbars}
          onThemeToggle={toggleTheme}
          onOpenPalette={() => setPaletteOpen(true)}
          onToggleSidebar={() => setSidebarOpen((v) => !v)}
          titleRef={titleRef}
          topicpathRef={topicpathRef}
        />

        <div id="contents" className="s26-contents">
          <div className="s26-article-shell">
            <article className="s26-article-card">
              <div ref={bodyRef} className="s26-body-slot" />
            </article>

            {config.hasRightbar && (
              <aside ref={rightbarRef} className="s26-rightbar-slot" />
            )}
          </div>

          <div ref={noteRef} className="s26-note-slot" />
          <div ref={attachRef} className="s26-attach-slot" />
          <div ref={footerRef} className="s26-footer-slot" />
        </div>
      </div>

      <div ref={toolbarRef} className="s26-toolbar-compat" hidden aria-hidden="true" />

      {showToolbars && (
        <MobileNav config={config} onOpenPalette={() => setPaletteOpen(true)} />
      )}

      {showFab && <Fab href={editHref} label={config.labels?.edit || 'Edit'} />}

      <CommandPalette
        open={paletteOpen}
        onClose={() => setPaletteOpen(false)}
        items={navItems}
        searchHref={searchHref}
        config={config}
      />
    </div>
  )
}
