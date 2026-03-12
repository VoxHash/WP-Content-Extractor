# Example 01 — Basic Setup

Step-by-step guide to set up WP Content Extractor for the first time.

## Scenario

You have a content API at `https://api.example.com/posts` that returns JSON data, and you want to automatically publish these posts to your WordPress site.

## Step 1: Install the Plugin

1. Download the plugin
2. Upload to `wp-content/plugins/wp-content-extractor/`
3. Activate via WordPress admin

## Step 2: Configure Source URL

1. Go to **Settings → Content Extractor**
2. Enter your source URL: `https://api.example.com/posts`
3. Click **Save Changes**

## Step 3: Implement Content Parsing

Edit `functions.php` and implement `ce_parse_and_publish_content()`:

```php
function ce_parse_and_publish_content( $body ) {
    $data = json_decode( $body, true );
    $created_posts = array();
    
    if ( ! empty( $data['posts'] ) ) {
        foreach ( $data['posts'] as $post_data ) {
            $new_post = array(
                'post_title'   => wp_strip_all_tags( $post_data['title'] ),
                'post_content' => $post_data['content'],
                'post_status'  => 'publish',
                'post_author'  => 1,
            );
            
            $post_id = wp_insert_post( $new_post );
            
            if ( $post_id && ! empty( $post_data['image'] ) ) {
                ce_set_featured_image( $post_id, $post_data['image'] );
            }
            
            if ( $post_id ) {
                $created_posts[] = $post_id;
            }
        }
    }
    
    return $created_posts;
}
```

## Step 4: Test

1. Wait for cron to run (or trigger manually)
2. Check WordPress posts to verify content was published
3. Verify featured images are attached

## Step 5: Monitor

- Check WordPress debug log for any errors
- Monitor cron execution via WP-CLI: `wp cron event list`
- Verify posts are being created regularly

## Troubleshooting

- **No posts created**: Check API response format matches your parsing logic
- **Images not downloading**: Verify image URLs are accessible
- **Cron not running**: Check WordPress cron is enabled
