# Example 01 — JSON API Source

Publish posts from a JSON API using the built-in parser (no custom code required).

## Scenario

Your source returns JSON like this:

```json
{
  "posts": [
    {
      "title": "Hello from the API",
      "content": "<p>Body HTML is allowed and sanitized.</p>",
      "date": "2026-10-05T12:00:00Z",
      "categories": ["News"],
      "tags": ["api", "json"],
      "image": "https://example.com/images/hello.jpg"
    }
  ]
}
```

Supported top-level shapes: a bare array of posts, or objects with `posts`, `data`, or `items`. WordPress REST (`title.rendered` / `content.rendered`) is also accepted.

## Steps

1. Install and activate the plugin (see [Installation](../installation.md)).
2. Open **Settings → Content Extractor**.
3. Set **Source URL** to your JSON endpoint, for example:
   - Custom API: `https://api.example.com/posts`
   - Another WordPress site: `https://wordpress.org/news/wp-json/wp/v2/posts`
4. Save changes.
5. Wait for the 30-minute cron, or trigger manually:

```php
if ( function_exists( 'ce_extract_content' ) ) {
    ce_extract_content();
}
```

## Verify

- New posts appear under **Posts** in wp-admin
- Duplicates with the same title are skipped
- Featured images attach when `image` (or resolved REST media) URLs are reachable
- With `WP_DEBUG` enabled, failures are written to the PHP error log

## Notes

- The plugin requests `?page=N` on the configured URL for batch pagination
- Default batch size is 5 posts per run
- Extend behavior with filters such as `ce_post_data`, `ce_post_status`, and `ce_post_author` (see [API](../api.md))
