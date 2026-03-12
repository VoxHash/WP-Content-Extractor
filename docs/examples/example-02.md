# Example 02 — HTML Content Parsing

Parse HTML content from a website and extract posts.

## Scenario

You want to extract blog posts from an HTML page that lists posts in a specific structure.

## Implementation

Edit `functions.php`:

```php
function ce_parse_and_publish_content( $body ) {
    $dom = new DOMDocument();
    @$dom->loadHTML( $body );
    $xpath = new DOMXPath( $dom );
    
    // Find all post containers (adjust selector to match your HTML structure)
    $post_nodes = $xpath->query( '//div[@class="post"]' );
    $created_posts = array();
    
    foreach ( $post_nodes as $post_node ) {
        // Extract title
        $title_nodes = $xpath->query( './/h2[@class="post-title"]', $post_node );
        $title = $title_nodes->length > 0 ? $title_nodes->item(0)->nodeValue : '';
        
        // Extract content
        $content_nodes = $xpath->query( './/div[@class="post-content"]', $post_node );
        $content = $content_nodes->length > 0 ? $content_nodes->item(0)->nodeValue : '';
        
        // Extract image URL
        $img_nodes = $xpath->query( './/img[@class="post-image"]', $post_node );
        $image_url = $img_nodes->length > 0 ? $img_nodes->item(0)->getAttribute('src') : '';
        
        if ( ! empty( $title ) && ! empty( $content ) ) {
            $new_post = array(
                'post_title'   => wp_strip_all_tags( $title ),
                'post_content' => wp_kses_post( $content ),
                'post_status'  => 'publish',
                'post_author'  => 1,
            );
            
            $post_id = wp_insert_post( $new_post );
            
            if ( $post_id && ! empty( $image_url ) ) {
                ce_set_featured_image( $post_id, $image_url );
            }
            
            if ( $post_id ) {
                $created_posts[] = $post_id;
            }
        }
    }
    
    return $created_posts;
}
```

## Notes

- Adjust XPath selectors to match your HTML structure
- Use `wp_kses_post()` to sanitize HTML content
- Handle missing elements gracefully
- Consider rate limiting for large sites
