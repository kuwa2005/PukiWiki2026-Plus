import Icon from './Icon.jsx'

export default function Fab ({ href, label }) {
  return (
    <a href={href} className="s26-fab" aria-label={label} title={label}>
      <Icon name="edit" />
    </a>
  )
}
