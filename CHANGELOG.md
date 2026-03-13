# Changelog — WP Content Extractor

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Content Parsing Implementation**: Complete implementation of `ce_parse_and_publish_content()` function
  - Support for JSON format (multiple structure variations)
  - Support for XML/RSS feeds
  - Support for HTML content parsing
  - Automatic content type detection
  - Duplicate post detection
  - Category and tag processing
  - Featured image support with multiple formats
- WordPress hooks and filters for extensibility:
  - `ce_before_parsing` - Action before parsing starts
  - `ce_after_parsing` - Action after parsing completes
  - `ce_post_data` - Filter to modify post data before creation
  - `ce_post_status` - Filter to set post status
  - `ce_post_author` - Filter to set post author
  - `ce_before_post_create` - Action before post creation
  - `ce_after_post_create` - Action after post creation
  - `ce_featured_image_url` - Filter to modify image URL
  - `ce_featured_image_set` - Action after featured image is set
- Comprehensive error logging throughout parsing process
- Support for multiple JSON structures (posts, data, items arrays, or single objects)
- Automatic category creation if categories don't exist
- Enhanced featured image handling with proper file type detection
- Comprehensive documentation structure
- Development goals and roadmap
- Contributing guidelines
- Security policy
- Code of conduct

### Changed
- Improved error handling in `ce_set_featured_image()` function
- Enhanced settings sanitization with custom callback
- Better return value handling in `ce_parse_and_publish_content()`
- Featured image function now handles multiple image formats (JPG, PNG, GIF, WebP)
- Improved image URL processing with relative to absolute URL conversion

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
