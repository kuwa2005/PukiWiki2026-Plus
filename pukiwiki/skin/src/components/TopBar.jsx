import Icon from './Icon.jsx'

export default function TopBar ({
  config,
  theme,
  onThemeToggle,
  onToggleSidebar,
  headRef,
  titleRef,
  topicpathRef,
  toolbar
}) {
  return (
    <header className="s26-topbar">
      <div className="s26-topbar-row s26-topbar-row--mobile">
        <button type="button" className="s26-icon-btn s26-menu-btn" onClick={onToggleSidebar} aria-label="Toggle sidebar">
          <Icon name="menu" />
        </button>
      </div>

      <div ref={headRef} className="s26-head-slot" />

      {toolbar}

      {config.isPage ? (
        <div className="s26-hero">
          <div className="s26-title-row">
            <div ref={titleRef} className="s26-title-slot" />
            <button type="button" className="s26-icon-btn s26-theme-toggle" onClick={onThemeToggle} aria-label="Toggle theme">
              <Icon name={theme === 'dark' ? 'sun' : 'moon'} />
            </button>
          </div>

          <nav ref={topicpathRef} className="s26-topicpath-slot" aria-label="Topic path" />
        </div>
      ) : (
        <div className="s26-topbar-util">
          <button type="button" className="s26-icon-btn s26-theme-toggle" onClick={onThemeToggle} aria-label="Toggle theme">
            <Icon name={theme === 'dark' ? 'sun' : 'moon'} />
          </button>
        </div>
      )}
    </header>
  )
}
