# Configuration

## Settings Page

Access via **Settings → Content Extractor** in WordPress admin.

| Setting | Type | Default | Description |
|---|---|---|---|
| Source URL | URL | - | The URL to extract content from. Must be a valid HTTP/HTTPS URL. |

## Plugin Constants

You can define these constants in `wp-config.php`:

| Constant | Type | Default | Description |
|---|---|---|---|
| `CE_DEBUG` | boolean | false | Enable debug logging |
| `CE_POSTS_PER_BATCH` | integer | 5 | Number of posts to process per batch |
| `CE_CRON_INTERVAL` | string | 'every_30_minutes' | Cron interval name |

Example:

```php
// In wp-config.php
define('CE_DEBUG', true);
define('CE_POSTS_PER_BATCH', 10);
```

## Cron Configuration

Default cron interval: **Every 30 minutes**

To change:

1. Edit `content-extractor.php`
2. Modify `ce_add_custom_cron_intervals()` function
3. Update the schedule in `wp_schedule_event()`

## Content Parsing

The `ce_parse_and_publish_content()` function needs to be implemented based on your content source format.

Current implementation returns an empty array. You'll need to:

1. Parse the response body (HTML, XML, JSON, etc.)
2. Extract post data (title, content, categories, tags)
3. Create WordPress posts using `wp_insert_post()`
4. Return array of created post IDs

## Featured Images

The plugin includes `ce_set_featured_image()` function for setting featured images:

```php
ce_set_featured_image( $post_id, $image_url );
```

This function:
- Downloads the image from the URL
- Uploads it to WordPress media library
- Sets it as the post's featured image
- Returns `true` on success, `false` on failure
