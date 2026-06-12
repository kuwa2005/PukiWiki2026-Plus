import { useEffect, useMemo, useRef, useState } from 'react'
import Icon from './Icon.jsx'

export default function CommandPalette ({ open, onClose, items, searchHref, config }) {
  const inputRef = useRef(null)
  const [query, setQuery] = useState('')

  useEffect(() => {
    if (open) {
      setQuery('')
      requestAnimationFrame(() => inputRef.current?.focus())
    }
  }, [open])

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return items.slice(0, 12)
    return items.filter((item) =>
      item.label.toLowerCase().includes(q) ||
      (item.group && item.group.toLowerCase().includes(q))
    ).slice(0, 16)
  }, [items, query])

  if (!open) return null

  return (
    <div className="s26-palette" role="presentation" onClick={onClose}>
      <div className="s26-palette-panel" role="dialog" aria-modal="true" aria-label="Command palette" onClick={(e) => e.stopPropagation()}>
        <div className="s26-palette-input-wrap">
          <Icon name="search" />
          <input
            ref={inputRef}
            type="search"
            className="s26-palette-input"
            placeholder="Jump to page or action…"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            onKeyDown={(e) => {
              if (e.key === 'Escape') onClose()
            }}
          />
        </div>
        <ul className="s26-palette-list">
          {filtered.map((item) => (
            <li key={item.key}>
              <a href={item.href} className="s26-palette-item" onClick={onClose}>
                <Icon name={item.icon || 'link'} />
                <span className="s26-palette-item-label">{item.label}</span>
                {item.group && <span className="s26-palette-item-meta">{item.group}</span>}
              </a>
            </li>
          ))}
          {filtered.length === 0 && (
            <li className="s26-palette-empty">No matches</li>
          )}
        </ul>
        <div className="s26-palette-foot">
          <a href={searchHref} className="s26-palette-foot-link" onClick={onClose}>
            Open full search →
          </a>
          {config.page && (
            <span className="s26-palette-foot-meta">{config.page}</span>
          )}
        </div>
      </div>
    </div>
  )
}
