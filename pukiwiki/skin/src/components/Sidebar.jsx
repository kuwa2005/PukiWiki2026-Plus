import Icon from './Icon.jsx'

export default function Sidebar ({ open, isDesktop, onToggle, config, menuRef }) {
  return (
    <>
      {!isDesktop && (
        <div
          className={`s26-sidebar-backdrop${open ? ' is-visible' : ''}`}
          onClick={onToggle}
          aria-hidden="true"
        />
      )}
      <aside className={`s26-sidebar${open ? ' is-open' : ''}`} aria-label="Wiki navigation">
        <div className="s26-sidebar-head">
          <a className="s26-brand" href={config.links?.top || '#'}>
            <span className="s26-brand-mark">W</span>
            <span className="s26-brand-text">{config.siteTitle}</span>
          </a>
          {!isDesktop && (
            <button type="button" className="s26-icon-btn s26-sidebar-close" onClick={onToggle} aria-label="Close sidebar">
              <Icon name="close" />
            </button>
          )}
        </div>

        <nav className="s26-sidebar-nav">
          {(config.nav || []).map((group) => (
            <div key={group.id} className={`s26-nav-group${group.id === 'search' ? ' s26-nav-group--search' : ''}`}>
              {group.label && <p className="s26-nav-label">{group.label}</p>}
              <ul className="s26-nav-list">
                {(group.items || []).map((item) => (
                  <li key={item.key}>
                    <a href={item.href} className="s26-nav-link">
                      <Icon name={item.icon || 'link'} />
                      <span>{item.label}</span>
                    </a>
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </nav>

        {config.hasMenu && (
          <div className="s26-sidebar-menu">
            <p className="s26-nav-label">Menu</p>
            <div ref={menuRef} className="s26-menu-slot" />
          </div>
        )}
      </aside>
    </>
  )
}
