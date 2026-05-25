# Changelog

## 1.1.0 — 2026-05-28

### Added

- ViewModel `Pronko\CacheToolbar\ViewModel\Toolbar` replaces the custom Block class
- Human-readable cache type labels in toolbar message ("Configuration" instead of "config")
- Error handling in SmartClear and FullClear controllers with PSR logger
- Request deduplication in JS — rapid clicks no longer fire multiple simultaneous requests
- `aria-live="polite"` and `role="status"` on toolbar message for screen reader support

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
