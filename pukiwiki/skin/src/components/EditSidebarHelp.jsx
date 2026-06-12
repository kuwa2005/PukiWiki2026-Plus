const FORMATTING_ITEMS = [
  { label: '見出し', syntax: '* 大見出し / ** 中見出し / *** 小見出し' },
  { label: '箇条書き', syntax: '- 項目 / -- 第2階層 / + 番号付き' },
  { label: '太字', syntax: "''太字''" },
  { label: '斜体', syntax: "'''斜体'''" },
  { label: '打ち消し', syntax: '%%打ち消し%%' },
  { label: 'インラインコード', syntax: '#code#' },
  { label: '内部リンク', syntax: '[[ページ名]] / [[ページ名|表示名]]' },
  { label: '外部リンク', syntax: '[https://example.com] / [URL 表示名]' },
  { label: '水平線', syntax: '----' },
  { label: '脚注', syntax: '((注)) / ((注> 本文))' },
  { label: '引用', syntax: '> 引用文' },
  { label: '定義リスト', syntax: ':用語|説明' }
]

const PLUGIN_ITEMS = [
  { label: '#head', syntax: '#head(画像.jpg) / #head(画像.jpg,180)' },
  { label: '#ref', syntax: '#ref(画像.png) / #ref(画像.png,middle,popup)' },
  { label: '#img', syntax: '#img(画像.png) / #img(ページ/画像.png)' },
  { label: '#comment', syntax: '#comment() — コメント欄を表示' },
  { label: '#include', syntax: '#include(ページ名) — 他ページを埋め込み' },
  { label: '#aname', syntax: '#aname(anchor) — リンク先アンカー' },
  { label: '#br / #hr', syntax: '#br — 改行 / #hr — 水平線' },
  { label: '#clear', syntax: '#clear — 回り込み解除' },
  { label: '#ls2', syntax: '#ls2 — サブページ一覧' }
]

function HelpSection ({ title, items, moreHref, moreLabel }) {
  return (
    <section className="s26-edit-help-section">
      <h2 className="s26-edit-help-title">{title}</h2>
      <dl className="s26-edit-help-list">
        {items.map((item) => (
          <div key={item.label} className="s26-edit-help-item">
            <dt className="s26-edit-help-label">{item.label}</dt>
            <dd className="s26-edit-help-syntax">
              <code>{item.syntax}</code>
            </dd>
          </div>
        ))}
      </dl>
      {moreHref && (
        <p className="s26-edit-help-more">
          <a href={moreHref} target="_blank" rel="noopener noreferrer">
            {moreLabel}
          </a>
        </p>
      )}
    </section>
  )
}

export default function EditSidebarHelp ({ config }) {
  const rulesHref = config?.links?.rules || config?.links?.help || ''
  const helpHref = config?.links?.help || ''

  return (
    <div className="s26-edit-help">
      <HelpSection
        title="テキスト整形のルール"
        items={FORMATTING_ITEMS}
        moreHref={rulesHref}
        moreLabel="全文を見る（FormattingRules）"
      />
      <HelpSection
        title="よく使われるプラグイン"
        items={PLUGIN_ITEMS}
        moreHref={helpHref}
        moreLabel="ヘルプページを開く"
      />
    </div>
  )
}
