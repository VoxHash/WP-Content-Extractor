# Usage

## Basic Usage

### Configuration

1. Navigate to **Settings → Content Extractor** in WordPress admin
2. Enter your **Source URL** (the URL to extract content from)
3. Click **Save Changes**

### Automatic Extraction

The plugin automatically runs every 30 minutes via WordPress cron:

- Fetches content from the configured URL
- Parses and extracts post data
- Publishes posts to WordPress
- Downloads and sets featured images
- Handles pagination automatically

### Manual Trigger

To trigger extraction manually:

```php
// In your theme's functions.php or a custom plugin
if (function_exists('ce_extract_content')) {
    ce_extract_content();
}
```

Or via WP-CLI (if you add a custom command):

```bash
wp eval 'ce_extract_content();'
```

## Advanced Usage

### Custom Cron Interval

Modify the cron interval in `content-extractor.php`:

```php
$schedules['every_15_minutes'] = array(
    'interval' => 900, // 15 minutes
    'display'  => __( 'Every 15 Minutes' ),
);
```

### Batch Size

Adjust posts per batch in `ce_extract_content()`:

```php
$posts_per_page = 10; // Process 10 posts per batch
```

### Content Parsing

Parsing for JSON, XML/RSS, and HTML is built in. Prefer filters over replacing the parser:

```php
add_filter( 'ce_post_data', function( $post_data ) {
    // Adjust title/content/image before publish
    return $post_data;
} );
```

See [Examples](examples/) and [API](api.md).

## Hooks & Filters

### Actions

- `ce_before_parsing` / `ce_after_parsing`
- `ce_before_post_create` / `ce_after_post_create`
- `ce_featured_image_set`

### Filters

- `ce_post_data` — Filter post fields before publishing
- `ce_post_status` — Control publish status
- `ce_post_author` — Control author ID
- `ce_featured_image_url` — Rewrite image URLs

## Examples

See [Examples](examples/) for detailed use cases.
