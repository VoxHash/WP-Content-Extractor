# Changelog — WP Content Extractor

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- Relicensed from MIT to **GPLv2 or later** to align with WordPress core and WordPress.org plugin licensing norms (plugin headers, LICENSE, README, docs)

## [1.1.0] - 2026-10-05

### Added
- Full content parsing for JSON, XML/RSS, and HTML sources (`ce_parse_and_publish_content()`)
- Automatic content-type detection with JSON fallback
- Duplicate post detection by title
- Category creation/mapping and tag support
- WordPress hooks and filters for extensibility (`ce_before_parsing`, `ce_after_parsing`, `ce_post_data`, `ce_post_status`, `ce_post_author`, `ce_before_post_create`, `ce_after_post_create`, `ce_featured_image_url`, `ce_featured_image_set`)
- Native WordPress REST API JSON support (`title.rendered` / `content.rendered`)
- Featured image handling for JPG, PNG, GIF, and WebP

### Changed
- Improved settings sanitization via `ce_sanitize_settings()`
- Stronger return-value handling from the parser
- Relative featured-image URLs resolved against the configured source URL
- Documentation kit aligned with the live GitHub repository (`VoxHash/WP-Content-Extractor`)
- Roadmap updated to reflect shipped parsing and feed support

### Fixed
- Cron hook `ce_daily_event` correctly connected to `ce_extract_content`
- Remote fetches use `wp_remote_get()` instead of `file_get_contents()`
- Undefined `$posts` / `$upload_dir` issues in extraction and featured-image paths
- Whitespace-only response bodies no longer trigger string-offset warnings
- RSS `media:` namespace XPath no longer emits undefined-prefix warnings
- Nested WP REST title/content arrays no longer crash sanitization
- CI version extraction now reads the plugin header reliably (avoids bad `vVersion:` tags)

### Removed
- `DEVELOPMENT_GOALS.md` (folded into `ROADMAP.md` / documentation kit)

## [1.0.0] - 2026-03-12

### Added
- Initial release
- Content extraction from external URLs
- WordPress cron scheduling (every 30 minutes)
- Admin settings page
- Featured image support
- Batch processing for posts
- Error logging functionality
