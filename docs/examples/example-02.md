# Example 02 — RSS / HTML Sources

Use the built-in XML/RSS and HTML parsers.

## RSS / Atom feed

1. Activate the plugin.
2. Set **Source URL** to a feed, for example: `https://wordpress.org/news/feed/`
3. Save. Cron (or a manual `ce_extract_content()` call) will:
   - Detect RSS/XML
   - Create posts from `<item>` / `<entry>` nodes
   - Map title, description/content, date, categories, and enclosure/image URLs when present

### Verify with WP-CLI (optional)

```bash
wp cron event run ce_daily_event
wp post list --post_type=post --fields=ID,post_title,post_date --posts_per_page=5
```

## HTML listing page

Point the source URL at an HTML page whose posts use common containers such as:

- `<article>`
- `.post` / `.article` / `.entry`
- Titles in `h1`/`h2` and body in `.content` / `.entry-content`

The parser extracts title, content fragments, and the first `<img>` (relative `src` values are resolved against the configured source URL).

## Customize without replacing the parser

Prefer hooks over rewriting `ce_parse_and_publish_content()`:

```php
add_filter( 'ce_post_status', function ( $status, $post_data ) {
    return 'draft'; // review before publishing
}, 10, 2 );

add_filter( 'ce_post_data', function ( $post_data ) {
    if ( ! empty( $post_data['title'] ) ) {
        $post_data['title'] = '[Syndicate] ' . $post_data['title'];
    }
    return $post_data;
} );
```

## Notes

- Invalid or empty responses are logged and skipped
- Duplicate titles are not republished
- For atypical HTML, adjust selectors via a small companion plugin that filters `ce_post_data`, or contribute an upstream enhancement
