# Cache Toolbar for Magento 2

> One-click cache clearing, right where you need it — without leaving the page.

[![Latest Version](https://img.shields.io/packagist/v/pronko/magento2-cache-toolbar?style=flat-square)](https://packagist.org/packages/pronko/magento2-cache-toolbar)
[![Total Downloads](https://img.shields.io/packagist/dt/pronko/magento2-cache-toolbar?style=flat-square)](https://packagist.org/packages/pronko/magento2-cache-toolbar)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue?style=flat-square)](https://www.php.net)
[![Magento](https://img.shields.io/badge/Magento-2.4.x-orange?style=flat-square)](https://github.com/magento/magento2)
[![MageOS](https://img.shields.io/badge/MageOS-2.4.6%2B-orange?style=flat-square)](https://mage-os.org)
[![License](https://img.shields.io/badge/License-OSL--3.0-green?style=flat-square)](LICENSE)

---

![Cache Toolbar Demo](docs/demo.gif)

---

## The problem

You save a config. Magento says "cache invalidated". You navigate to System → Cache Management. Select cache types. Click Flush. Go back to where you were.

**That's 6 steps for something that should take 1.**

## The solution

A smart status bar appears automatically when your cache is outdated — with a single "Smart Clear" button that clears only the invalidated types instantly, without leaving the page.

---

## Features

- **Smart Clear** — clears only the cache types that are actually invalidated, from your configured list
- **Full Clear** — clears all cache types and flushes the cache pool when you need a clean slate
- **Zero-delay detection** — cache status is checked server-side on every page load, bar renders immediately with no AJAX flicker
- **Keyboard shortcut** — `Ctrl+Shift+C` triggers Smart Clear from anywhere in the admin
- **Auto-dismiss** — success message disappears after 3 seconds, stays out of your way
- **Configurable** — choose which cache types "Smart Clear" includes via Stores → Configuration
- **ACL-aware** — toolbar only renders for admin users with cache clear permission

---

## Installation

```bash
composer require pronko/magento2-cache-toolbar
bin/magento module:enable Pronko_CacheToolbar
bin/magento setup:upgrade
```

---

## Configuration

**Stores → Configuration → Pronko → Cache Toolbar**

| Setting | Default | Description |
|---|---|---|
| Enable Toolbar | Yes | Show/hide the toolbar |
| Keyboard Shortcut | Yes | Enable `Ctrl+Shift+C` |
| Smart Clear Types | 7 types | Which cache types Smart Clear targets |
| Show Fast Admin Promo | Yes | Promotional banner for Fast Admin (disable for client deployments) |

---

## How it works

Cache status is checked **server-side on every page load** via `TypeListInterface`.

```
Page loads
  └─ PHP checks TypeListInterface::getInvalidated()
      ├─ Cache outdated → amber bar + Smart Clear / Full Clear buttons
      └─ Cache clean    → bar hidden

User clicks Smart Clear
  └─ AJAX POST → Controller
      └─ intersect(configured types, invalidated types)
          └─ cleanType() for each
              └─ "Cache cleared · 2 types · 0.1s" → auto-dismiss after 3s

User clicks Full Clear
  └─ AJAX POST → Controller
      └─ cleanType() for all types + cache pool flush
          └─ "Cache cleared · 18 types · 0.4s" → auto-dismiss after 3s
```

The bar state on the next page load always reflects reality — no stale UI possible.

---

## Smart Clear vs Full Clear

| | Smart Clear | Full Clear |
|---|---|---|
| Which types | Invalidated types from your configured list | All registered cache types |
| Cache pool flush | No | Yes |
| CLI equivalent | `cache:clean config full_page` | `cache:flush` |
| Use case | After a config save or deploy | When something is deeply wrong |

---

## Requirements

- PHP 8.2+
- Magento 2.4.4+ / MageOS 2.4.6+
- [`pronko/module-base`](https://github.com/pronkoconsulting/module-base)

---

## Contributing

Pull requests are welcome. For major changes, please open an issue first.

```bash
git clone https://github.com/pronkoconsulting/magento2-cache-toolbar.git
composer install
```

---

## License

[Open Software License 3.0 (OSL-3.0)](LICENSE)

---

<p align="center">
  Tired of slow Magento admin? &nbsp;
  <a href="https://www.pronkoconsulting.com/fast-admin?utm_source=cache-toolbar&utm_medium=readme&utm_campaign=oss-module">
    <strong>⚡ Fast Admin loads orders in 0.3s →</strong>
  </a>
</p>
