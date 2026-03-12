# API Reference

## Functions

### `ce_extract_content()`

Main function that extracts content from the configured URL and publishes posts.

**Returns:** `void`

**Usage:**
```php
ce_extract_content();
```

**Hooks:**
- `ce_before_extraction` - Fires before extraction starts
- `ce_after_extraction` - Fires after extraction completes

### `ce_parse_and_publish_content( $body )`

Parses the response body and publishes posts to WordPress.

**Parameters:**
- `$body` (string) - The HTML/XML/JSON response body to parse

**Returns:** `array` - Array of created post IDs

**Usage:**
```php
$response = wp_remote_get( $url );
$body = wp_remote_retrieve_body( $response );
$post_ids = ce_parse_and_publish_content( $body );
```

### `ce_set_featured_image( $post_id, $image_url )`

Downloads and sets a featured image for a post.

**Parameters:**
- `$post_id` (int) - WordPress post ID
- `$image_url` (string) - URL of the image to download

**Returns:** `bool` - `true` on success, `false` on failure

**Usage:**
```php
$success = ce_set_featured_image( 123, 'https://example.com/image.jpg' );
```

### `ce_log_error( $message )`

Logs an error message if `WP_DEBUG` is enabled.

**Parameters:**
- `$message` (string) - Error message to log

**Returns:** `void`

**Usage:**
```php
ce_log_error( 'Failed to fetch content from ' . $url );
```

## Hooks

### Actions

#### `ce_before_extraction`

Fires before content extraction begins.

```php
add_action( 'ce_before_extraction', function() {
    // Your code here
});
```

#### `ce_after_extraction`

Fires after content extraction completes.

```php
add_action( 'ce_after_extraction', function( $post_ids ) {
    // $post_ids is array of created post IDs
});
```

### Filters

#### `ce_source_url`

Filter the source URL before fetching.

```php
add_filter( 'ce_source_url', function( $url ) {
    // Modify URL
    return $url;
});
```

#### `ce_post_data`

Filter post data before publishing.

```php
add_filter( 'ce_post_data', function( $post_data, $source_data ) {
    // Modify post data
    return $post_data;
}, 10, 2 );
```

## Options

### `ce_settings`

Stores plugin settings.

**Structure:**
```php
array(
    'ce_url' => 'https://example.com/api/posts'
)
```

**Get:**
```php
$options = get_option( 'ce_settings' );
$url = $options['ce_url'] ?? '';
```

**Update:**
```php
update_option( 'ce_settings', array(
    'ce_url' => 'https://new-url.com'
) );
```

### `ce_current_page`

Stores the current page number for pagination.

**Get:**
```php
$page = get_option( 'ce_current_page', 1 );
```

**Update:**
```php
update_option( 'ce_current_page', 2 );
```
