const paths = {
  home: 'M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z',
  menu: 'M4 7h16M4 12h16M4 17h16',
  close: 'M6 6l12 12M18 6L6 18',
  search: 'M11 19a8 8 0 100-16 8 8 0 000 16zm10 2l-4.3-4.3',
  edit: 'M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z',
  moon: 'M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z',
  sun: 'M12 4V2m0 20v-2M4 12H2m20 0h-2M5 5l-1.4-1.4M20.4 20.4L19 19M5 19l-1.4 1.4M20.4 5.6L19 7',
  list: 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01',
  clock: 'M12 8v5l3 2M12 22a10 10 0 110-20 10 10 0 010 20z',
  link: 'M10 13a5 5 0 007.1 0l1-1a5 5 0 00-7.1-7.1l-1 1M14 11a5 5 0 00-7.1 0l-1 1a5 5 0 007.1 7.1l1-1',
  command: 'M6 9H4.5a2.5 2.5 0 010-5H6M18 9h1.5a2.5 2.5 0 000-5H18M6 15H4.5a2.5 2.5 0 000 5H6M18 15h1.5a2.5 2.5 0 000 5H18',
  diff: 'M8 8h12M8 12h8M8 16h4M4 8h.01M4 12h.01M4 16h.01',
  upload: 'M12 16V4m0 0l-4 4m4-4l4 4M4 20h16',
  freeze: 'M12 2v20M2 12h20M5 5l14 14M19 5L5 19',
  backup: 'M12 8v8m-4-4h8M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2',
  reload: 'M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006.3 6.3M4 15a8 8 0 0013.7 2.7',
  new: 'M12 5v14M5 12h14',
  help: 'M12 18h.01M8.2 8.2A3.8 3.8 0 0112 7c2.1 0 3.8 1.7 3.8 3.8 0 2.8-3.8 2.8-3.8 5.2',
  login: 'M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3',
  logout: 'M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9',
  rss: 'M4 11a9 9 0 019 9M4 4a16 16 0 0116 16M5 18a1 1 0 100-2 1 1 0 000 2z',
}

export default function Icon ({ name }) {
  const d = paths[name] || paths.link
  return (
    <svg className="s26-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.75" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
      <path d={d} />
    </svg>
  )
}
