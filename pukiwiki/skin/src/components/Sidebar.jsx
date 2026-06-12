import EditSidebarHelp from './EditSidebarHelp.jsx'
import Icon from './Icon.jsx'

export default function Sidebar ({ open, isDesktop, onToggle, menuRef, config }) {
  const isEdit = Boolean(config?.isEdit)
  const topHref = config?.links?.top || ''
  const topLabel = config?.labels?.top || 'Top'
  const siteTitle = config?.pageTitle || config?.siteTitle || ''
  const isLoggedIn = Boolean(config?.isLoggedIn)
  const authHref = isLoggedIn
    ? (config?.links?.logout || '')
    : (config?.links?.login || '')
  const authLabel = isLoggedIn ? 'ログアウト' : 'ログイン'

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
          {isEdit
            ? <EditSidebarHelp config={config} />
            : <div ref={menuRef} className="s26-menu-slot" />}
          {isEdit && (
            <div ref={menuRef} className="s26-menu-slot s26-menu-slot--hidden" aria-hidden="true" />
          )}
        </div>

        {authHref && (
          <a href={authHref} className="s26-sidebar-login-hint" aria-label={authLabel}>.</a>
        )}
      </aside>
    </>
  )
}
