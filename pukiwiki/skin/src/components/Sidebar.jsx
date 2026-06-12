import Icon from './Icon.jsx'

export default function Sidebar ({ open, isDesktop, onToggle, menuRef, config }) {
  const topHref = config?.links?.top || ''
  const topLabel = config?.labels?.top || 'Top'
  const siteTitle = config?.pageTitle || config?.siteTitle || ''

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
        <div className="s26-sidebar-brand">
          {!isDesktop && (
            <button type="button" className="s26-icon-btn s26-sidebar-close" onClick={onToggle} aria-label="Close sidebar">
              <Icon name="close" />
            </button>
          )}
          {topHref && (
            <a href={topHref} className="s26-sidebar-home s26-icon-btn" title={topLabel} aria-label={topLabel}>
              <Icon name="home" />
            </a>
          )}
          {siteTitle && (
            topHref
              ? (
                <a href={topHref} className="s26-sidebar-site-title">
                  {siteTitle}
                </a>
                )
              : (
                <span className="s26-sidebar-site-title">{siteTitle}</span>
                )
          )}
        </div>

        <div className="s26-sidebar-menu">
          <div ref={menuRef} className="s26-menu-slot" />
        </div>
      </aside>
    </>
  )
}
