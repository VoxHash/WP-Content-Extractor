# Changelog — WP Content Extractor

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Comprehensive documentation structure
- Development goals and roadmap
- Contributing guidelines
- Security policy
- Code of conduct

### Changed
- Improved error handling in `ce_set_featured_image()` function
- Enhanced settings sanitization with custom callback
- Better return value handling in `ce_parse_and_publish_content()`

### Fixed
- Fixed missing cron hook connection (`ce_daily_event` now properly connected to `ce_extract_content`)
- Fixed undefined variable `$posts` in content extraction function
- Fixed security issue: replaced `file_get_contents()` with `wp_remote_get()` for remote URLs
- Fixed undefined variable `$upload_dir` in featured image function
- Added proper error handling for file operations

## [1.0.0] - 2026-03-12

### Added
- Initial release
- Content extraction from external URLs
- WordPress cron scheduling (every 30 minutes)
- Admin settings page
- Featured image support
- Batch processing for posts
- Error logging functionality
