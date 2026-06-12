import Icon from './Icon.jsx'

export default function TopBar ({
  config,
  theme,
  isLoggedIn,
  showPageToolbar,
  onThemeToggle,
  onOpenPalette,
  onToggleSidebar,
  titleRef,
  topicpathRef
}) {
  return (
    <header className="s26-topbar">
      <div className="s26-topbar-row">
        <button type="button" className="s26-icon-btn s26-menu-btn" onClick={onToggleSidebar} aria-label="Toggle sidebar">
          <Icon name="menu" />
        </button>

        <button type="button" className="s26-search-trigger" onClick={onOpenPalette}>
          <Icon name="search" />
          <span>Search pages…</span>
          <kbd className="s26-kbd">⌘K</kbd>
        </button>

        <div className="s26-topbar-actions">
          {showPageToolbar && config.rw && config.isPage && config.isRead && config.links?.edit && (
            <a className="s26-btn s26-btn--ghost s26-btn--sm" href={config.links.edit}>
              <Icon name="edit" />
              <span className="s26-hide-mobile">{config.labels?.edit || 'Edit'}</span>
            </a>
          )}
          <button type="button" className="s26-icon-btn" onClick={onThemeToggle} aria-label="Toggle theme">
            <Icon name={theme === 'dark' ? 'sun' : 'moon'} />
          </button>
        </div>
      </div>

      {config.isPage && (
        <div className="s26-hero">
          <div ref={titleRef} className="s26-title-slot" />
          <nav ref={topicpathRef} className="s26-topicpath-slot" aria-label="Topic path" />
        </div>
      )}
    </header>
  )
}
