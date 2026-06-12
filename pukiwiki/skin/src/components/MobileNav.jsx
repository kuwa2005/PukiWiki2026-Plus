import Icon from './Icon.jsx'

const ITEMS = [
  { key: 'top', icon: 'home' },
  { key: 'search', icon: 'search' },
  { key: 'recent', icon: 'clock' },
  { key: 'edit', icon: 'edit', needsPage: true, needsRw: true },
  { key: 'list', icon: 'list' },
]

export default function MobileNav ({ config, onOpenPalette }) {
  const links = config.links || {}
  const labels = config.labels || {}
  const isLoggedIn = Boolean(config.isLoggedIn)

  return (
    <nav className="s26-mobile-nav" aria-label="Mobile navigation">
      {ITEMS.filter((item) => {
        if (item.needsPage && !config.isPage) return false
        if (item.needsRw && !config.rw) return false
        if (item.key === 'edit' && !isLoggedIn) return false
        return links[item.key]
      }).map((item) => (
        <a key={item.key} href={links[item.key]} className="s26-mobile-nav-item">
          <Icon name={item.icon} />
          <span>{labels[item.key] || item.key}</span>
        </a>
      ))}
      <button type="button" className="s26-mobile-nav-item" onClick={onOpenPalette}>
        <Icon name="command" />
        <span>Command</span>
      </button>
    </nav>
  )
}
