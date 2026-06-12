import Icon from './Icon.jsx'

export default function Sidebar ({ open, isDesktop, onToggle, menuRef }) {
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
        {!isDesktop && (
          <div className="s26-sidebar-head s26-sidebar-head--mobile">
            <button type="button" className="s26-icon-btn s26-sidebar-close" onClick={onToggle} aria-label="Close sidebar">
              <Icon name="close" />
            </button>
          </div>
        )}

        <div className="s26-sidebar-menu">
          <div ref={menuRef} className="s26-menu-slot" />
        </div>
      </aside>
    </>
  )
}
