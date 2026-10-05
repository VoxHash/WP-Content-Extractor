# Roadmap — WP Content Extractor

Current release: **1.1.0** (JSON / XML / RSS / HTML parsing, cron extraction, featured images, extensibility hooks).

## Shipped

- Content parsing for JSON, XML/RSS, and HTML
- Duplicate detection by post title
- Featured image download and attachment
- WordPress action/filter hooks for customization
- WordPress REST API field shape (`title.rendered` / `content.rendered`)
- Admin settings page for a single source URL
- Fixed 30-minute cron schedule

## Near term (1.2.x)

- **Multiple source URLs**: Extract from more than one feed/API
- **Configurable batch size**: Expose posts-per-run in settings (currently hardcoded to 5)
- **Configurable cron interval**: Let admins choose schedule beyond every 30 minutes
- **Content filtering**: Keywords, date ranges, and category include/exclude
- **Manual “Run now” button** on the settings screen (no theme/code required)

## Next (1.3.x)

- **Custom post types**: Publish to CPT targets
- **Taxonomy mapping**: Map source categories/tags to existing WordPress terms
- **Content transformation hooks UI**: Documented recipes for common transforms
- **REST endpoint** to trigger extraction manually with capability checks

## Later

- Webhook-triggered extraction
- Extraction success/failure stats (dashboard widget)
- Multisite-aware settings
- Optional image optimization and translation workflows
