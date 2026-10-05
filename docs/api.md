# API Reference

## Functions

### `ce_extract_content()`

Fetches the configured source URL (with `?page=N` pagination) and publishes parsed posts.

**Returns:** `void`

**Usage:**
```php
ce_extract_content();
```

### `ce_parse_and_publish_content( $body )`

Detects JSON, XML/RSS, or HTML and creates WordPress posts.

**Parameters:**
- `$body` (string) — Response body to parse

**Returns:** `array` — Created post IDs

**Usage:**
```php
$response = wp_remote_get( $url );
$body = wp_remote_retrieve_body( $response );
$post_ids = ce_parse_and_publish_content( $body );
```

### `ce_detect_content_type( $body )`

**Returns:** `string` — `json`, `xml`, `rss`, `html`, or `unknown`

### `ce_set_featured_image( $post_id, $image_url )`

Downloads an image and sets it as the post thumbnail.

**Returns:** `bool`

### `ce_log_error( $message )`

Writes to the PHP error log when `WP_DEBUG` is enabled.

## Hooks

### Actions

| Hook | Arguments | When |
|---|---|---|
| `ce_before_parsing` | `$body` | Before parse begins |
| `ce_after_parsing` | `$created_posts` | After parse completes |
| `ce_before_post_create` | `$new_post`, `$post_data` | Before `wp_insert_post` |
| `ce_after_post_create` | `$post_id`, `$post_data` | After successful insert |
| `ce_featured_image_set` | `$post_id`, `$attach_id`, `$image_url` | After thumbnail is set |

```php
add_action( 'ce_after_post_create', function( $post_id, $post_data ) {
    // e.g. notify, set meta, etc.
}, 10, 2 );
```

### Filters

| Hook | Arguments | Purpose |
|---|---|---|
| `ce_post_data` | `$post_data` | Mutate parsed fields before create |
| `ce_post_status` | `$status`, `$post_data` | Default `publish` |
| `ce_post_author` | `$author_id`, `$post_data` | Default `1` |
| `ce_featured_image_url` | `$image_url`, `$post_id` | Rewrite image URL before download |

```php
add_filter( 'ce_post_status', function( $status, $post_data ) {
    return 'draft';
}, 10, 2 );
```

## Options

### `ce_settings`

```php
array(
    'ce_url' => 'https://example.com/feed/'
)
```

### `ce_current_page`

Integer page cursor used for `?page=N` batching (deleted when a batch returns fewer than the batch size).
