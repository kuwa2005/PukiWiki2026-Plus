import Icon from './Icon.jsx'

export default function ToolbarRow ({ showToolbars, toolbarRef, logoutHref, logoutLabel }) {
  if (!showToolbars) {
    return <div ref={toolbarRef} className="s26-toolbar-adopt-only" aria-hidden="true" />
  }

  return (
    <div className="s26-toolbar-row">
      <div ref={toolbarRef} className="s26-toolbar-slot" />
      {logoutHref && (
        <a
          href={logoutHref}
          className="s26-toolbar-action"
          title={logoutLabel}
          aria-label={logoutLabel}
        >
          <Icon name="logout" />
        </a>
      )}
    </div>
  )
}
