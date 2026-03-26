import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'Modular Monolith Laravel',
  description: 'Laravel package for building modular monolith applications',
  appearance: 'dark',
  themeConfig: {
    logo: '/uncoverthefuture.svg',
    siteTitle: 'Modular Monolith Laravel',
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Installation', link: '/installation' },
      { text: 'Quick Start', link: '/quickstart' },
      { text: 'API', link: '/api-response' }
    ],
    sidebar: [
      {
        text: 'Getting Started',
        items: [
          { text: 'Introduction', link: '/' },
          { text: 'Installation', link: '/installation' },
          { text: 'Quick Start', link: '/quickstart' },
          { text: 'Configuration', link: '/configuration' }
        ]
      },
      {
        text: 'Reference',
        items: [
          { text: 'Base Classes', link: '/base-classes' },
          { text: 'API Response', link: '/api-response' }
        ]
      }
    ],
    socialLinks: [
      { icon: 'github', link: 'https://github.com/uncoverthefuture-org/modular-monlith-laravel' }
    ],
    footer: {
      message: 'Released under the MIT License',
      copyright: 'Copyright © 2024 Uncover'
    },
    search: {
      provider: 'local'
    }
  },
  head: [
    ['link', { rel: 'icon', href: '/uncoverthefuture.svg', type: 'image/svg+xml' }]
  ]
})
