// PukiWiki2026 Plus — skin2026 UI helpers (mobile nav, theme toggle)
/* eslint-env browser */
(function () {
  'use strict'

  function ready (fn) {
    if (document.readyState !== 'loading') {
      fn()
    } else {
      document.addEventListener('DOMContentLoaded', fn)
    }
  }

  ready(function () {
    var navToggle = document.getElementById('skin2026-nav-toggle')
    var navPanel = document.getElementById('skin2026-nav-panel')
    var themeToggle = document.getElementById('skin2026-theme-toggle')
    var storageKey = 'pukiwiki-skin2026-theme'

    if (navToggle && navPanel) {
      navToggle.addEventListener('click', function () {
        var open = navPanel.classList.toggle('is-open')
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false')
      })
      document.addEventListener('click', function (e) {
        if (!navPanel.classList.contains('is-open')) return
        if (navPanel.contains(e.target) || navToggle.contains(e.target)) return
        navPanel.classList.remove('is-open')
        navToggle.setAttribute('aria-expanded', 'false')
      })
    }

    function applyTheme (mode) {
      document.documentElement.setAttribute('data-theme', mode)
      if (themeToggle) {
        themeToggle.setAttribute('aria-pressed', mode === 'dark' ? 'true' : 'false')
        themeToggle.textContent = mode === 'dark' ? '\u2600' : '\u263E'
      }
    }

    var saved = null
    try {
      saved = localStorage.getItem(storageKey)
    } catch (e) { /* ignore */ }

    if (saved === 'light' || saved === 'dark') {
      applyTheme(saved)
    } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      applyTheme('dark')
    } else {
      applyTheme('light')
    }

    if (themeToggle) {
      themeToggle.addEventListener('click', function () {
        var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'
        applyTheme(next)
        try {
          localStorage.setItem(storageKey, next)
        } catch (e) { /* ignore */ }
      })
    }
  })
})()
