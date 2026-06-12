import Icon from './Icon.jsx'

export default function TopBar ({
  config,
  theme,
  onThemeToggle,
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

        <div className="s26-topbar-actions">
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
