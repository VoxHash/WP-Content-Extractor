# Configuration

## Settings Page

Access via **Settings → Content Extractor** in WordPress admin.

| Setting | Type | Default | Description |
|---|---|---|---|
| Source URL | URL | _(empty)_ | HTTP(S) URL to fetch. Cron appends `?page=N` for batching. |

Settings are stored in the `ce_settings` option (`ce_url` key) and sanitized with `esc_url_raw()`.

## Built-in defaults (code)

These are not admin fields yet (see [ROADMAP.md](../ROADMAP.md)); change them in `content-extractor.php` if needed:

| Behavior | Current value | Location |
|---|---|---|
| Posts per batch | `5` | `$posts_per_page` in `ce_extract_content()` |
| Cron interval | Every 30 minutes (`1800` seconds) | `ce_add_custom_cron_intervals()` |
| Cron hook | `ce_daily_event` → `ce_extract_content` | `content-extractor.php` |

## Cron

Default: **every 30 minutes**.

To use a different interval:

1. Add a schedule in `ce_add_custom_cron_intervals()`
2. Reschedule with `wp_schedule_event()` (clear the old event on deactivation/reactivation)
3. Confirm with `wp cron event list`

## Content formats

No custom parser is required. The plugin detects and handles:

- **JSON** — array or `{ posts|data|items: [...] }`, plus WordPress REST nested `rendered` fields
- **XML / RSS / Atom** — `item` / `entry` nodes
- **HTML** — `article` and common `.post` / `.entry` containers

Customize via filters in [API](api.md) rather than replacing the parser.

## Featured images

When parsed data includes an `image` URL (or RSS enclosure), `ce_set_featured_image()` downloads it with `wp_remote_get()`, stores it in the media library, and sets the thumbnail. Relative URLs are resolved against the configured source host when possible.

## Debug logging

Enable WordPress debug logging:

```php
// wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

Plugin errors are written with `ce_log_error()` only when `WP_DEBUG` is true.
