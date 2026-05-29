# Changelog

## 1.1.4 — 2026-05-29

### Changed

- Updated README title to include MageOS
- Updated README screenshots

## 1.1.3 — 2026-05-29

### Changed

- `SmartClear` and `FullClear` controllers dispatch `pronko_cache_toolbar_clear_after` event after a successful cache clear, carrying `action`, `cache_types`, `duration_ms`, and `origin=toolbar`

## 1.1.2 — 2026-05-29

### Fixed

- `SuppressCacheOutdatedNotification` plugin now falls back to Magento's native cache invalidation message for admins who lack the `Pronko_CacheToolbar::cache_clear` ACL permission — previously those admins received no notification at all

## 1.1.1 — 2026-05-29

### Changed

- ACL: added `translate="title"` to all resource nodes for i18n readiness
- ACL: shortened role-tree leaf titles (`Clear Cache via Cache Toolbar` → `Clear Cache`, `Cache Toolbar Configuration` → `Configuration`) — no permission or id changes

## 1.1.0 — 2026-05-28

### Added

- ViewModel `Pronko\CacheToolbar\ViewModel\Toolbar` replaces the custom Block class
- Human-readable cache type labels in toolbar message ("Configuration" instead of "config")
- Error handling in SmartClear and FullClear controllers with PSR logger writing to `var/log/pronko.log`
- Request deduplication in JS — rapid clicks no longer fire multiple simultaneous requests
- `aria-live="polite"` and `role="status"` on toolbar message for screen reader support
- `pronko/magento2-core` dependency — shared admin tab, parent ACL resource, and logger

### Changed

- ACL resources restructured under `Pronko_Core::pronko` parent resource owned by `Pronko_Core`
- Pronko Consulting admin tab definition moved to `Pronko_Core`

### Removed

- `Block/Adminhtml/Toolbar.php` — replaced by ViewModel (breaking change)

## 1.0.3 — 2026-05-27

### Added

- i18n/en_US.csv with all translatable strings

### Changed

- Toolbar message updated from "Cache outdated" to "Cache invalidated" to align with Magento terminology

## 1.0.2 — 2026-05-26

### Fixed

- PHP version constraint corrected to `>=8.1.0` to match Magento 2.4.4+ support
- Added `magento/module-admin-notification` as an explicit Composer dependency
- Added `Magento_AdminNotification` to module load sequence
- Controllers now implement `HttpPostActionInterface`

## 1.0.1 — 2026-05-25

### Fixed

- ACL resources restructured under a `Pronko Consulting` parent group

## 1.0.0 — 2026-05-17

### Added

- Smart Clear button — clears only the invalidated cache types in one click
- Full Clear button — clears all cache types and flushes the cache pool
- Cache status bar appears automatically on page load when cache is outdated
- Keyboard shortcut `Ctrl+Shift+C` to trigger Smart Clear from any admin page
- Configurable Smart Clear cache types via Stores → Configuration → Pronko → Cache Toolbar
- Auto-dismiss success message after 3 seconds
- Fast Admin promo banner — shown after first Smart Clear, dismissible for 30 days
