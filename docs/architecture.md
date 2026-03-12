# Architecture

## Overview

WP Content Extractor is a WordPress plugin that automates content extraction and publishing via WordPress cron.

## File Structure

```
wp-content-extractor/
├── content-extractor.php    # Main plugin file
├── functions.php            # Core functions
└── README.md               # Documentation
```

## Components

### Main Plugin File (`content-extractor.php`)

**Responsibilities:**
- Plugin initialization
- Settings page registration
- Cron scheduling
- Settings API integration

**Key Functions:**
- `ce_register_settings_page()` - Registers admin settings page
- `ce_register_settings()` - Registers settings with WordPress Settings API
- `ce_add_custom_cron_intervals()` - Adds custom cron intervals
- `ce_extract_content()` - Main extraction function
- `ce_log_error()` - Error logging

### Functions File (`functions.php`)

**Responsibilities:**
- Content parsing logic
- Post publishing
- Featured image handling

**Key Functions:**
- `ce_parse_and_publish_content()` - Parses content and creates posts
- `ce_set_featured_image()` - Downloads and sets featured images

## Data Flow

```
1. WordPress Cron Trigger
   ↓
2. ce_extract_content()
   ↓
3. Fetch content from URL (wp_remote_get)
   ↓
4. Parse content (ce_parse_and_publish_content)
   ↓
5. Create WordPress posts (wp_insert_post)
   ↓
6. Download featured images (ce_set_featured_image)
   ↓
7. Update pagination state
```

## WordPress Integration

### Settings API

Uses WordPress Settings API for secure settings management:
- `register_setting()` - Registers settings group
- `add_settings_section()` - Creates settings section
- `add_settings_field()` - Adds individual fields

### Cron System

Uses WordPress cron for scheduled tasks:
- `wp_schedule_event()` - Schedules recurring event
- `add_action()` - Hooks function to cron event
- `wp_next_scheduled()` - Checks if event is scheduled

### Security

- Uses `wp_remote_get()` for HTTP requests (not `file_get_contents()`)
- Sanitizes all user input with `esc_url_raw()`, `esc_attr()`
- Validates data before processing
- Uses WordPress nonces for form submissions

## Error Handling

- All remote requests check for `WP_Error`
- File operations validate success
- Errors logged via `error_log()` when `WP_DEBUG` is enabled
- Functions return `false` on failure for easy error checking

## Extensibility

The plugin is designed to be extensible:

- **Hooks**: Actions and filters for customization
- **Modular Functions**: Easy to override or extend
- **Clear Separation**: Settings, extraction, and parsing are separated

## Future Architecture Considerations

- REST API endpoints for manual triggering
- Background processing for large batches
- Caching layer for remote requests
- Queue system for failed extractions
