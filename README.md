# ⚡ Cache Toolbar for Magento 2

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

A smart status bar appears automatically when your cache is outdated — with a single "Smart Clear" button that clears the right cache types instantly, without leaving the page.

---

## Features

- **Smart Clear** — clears the 7 most common cache types in one click (CONFIG, LAYOUT, BLOCK_HTML, COMPILED_CONFIG, EAV, FPC, TRANSLATE)
- **Full Clear** — clears all cache types when you need a clean slate
- **Auto-detect** — bar appears automatically when cache is invalidated, rendered server-side with zero delay
- **Cross-tab sync** — cleared cache on another tab? The bar updates here too via polling
- **Keyboard shortcut** — `Ctrl+Shift+C` triggers Smart Clear from anywhere in the admin
- **Auto-dismiss** — success message disappears after 3 seconds, stays out of your way
- **Configurable** — choose which cache types "Smart Clear" includes

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
| Status Polling Interval | 30s | How often to check cache status across tabs (0 = off) |
| Smart Clear Types | 7 types | Choose which cache types Smart Clear includes |
| Show Fast Admin Promo | Yes | Promotional banner for Fast Admin (can be disabled) |

---

## Compatibility

| Platform | Version |
|---|---|
| Magento Open Source | 2.4.4 — 2.4.x |
| Adobe Commerce | 2.4.4 — 2.4.x |
| MageOS | 2.4.6+ |
| PHP | 8.2, 8.3, 8.4, 8.5 |

---

## How it works

The cache status is checked **server-side on page load** — no AJAX delay, no flash of content. The bar renders immediately with the correct state.

```
Page loads
  └─ PHP checks TypeListInterface
      ├─ Cache outdated → amber bar + Smart Clear / Full Clear buttons
      └─ Cache clean    → bar hidden

User clicks Smart Clear
  └─ AJAX → Controller → flush 7 cache types → green "Cache cleared · 7 types · 0.4s"
      └─ auto-dismiss after 3s

Background polling every 30s
  └─ Cache cleared in another tab → bar hides automatically
  └─ Cache invalidated elsewhere  → bar appears automatically
```

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
