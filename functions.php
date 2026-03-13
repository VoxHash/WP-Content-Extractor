<?php

/**
 * Parse and publish content from various formats (JSON, XML, HTML)
 *
 * @param string $body The response body to parse
 * @return array Array of created post IDs
 */
function ce_parse_and_publish_content( $body ) {
    if ( empty( $body ) ) {
        ce_log_error( 'Content Extractor: Empty body received' );
        return array();
    }

    $created_posts = array();

    // Fire action before parsing
    do_action( 'ce_before_parsing', $body );

    // Detect content type and parse accordingly
    $content_type = ce_detect_content_type( $body );
    
    switch ( $content_type ) {
        case 'json':
            $created_posts = ce_parse_json_content( $body );
            break;
        case 'xml':
        case 'rss':
            $created_posts = ce_parse_xml_content( $body );
            break;
        case 'html':
            $created_posts = ce_parse_html_content( $body );
            break;
        default:
            ce_log_error( 'Content Extractor: Unknown content type: ' . $content_type );
            // Try JSON as fallback
            $created_posts = ce_parse_json_content( $body );
            break;
    }

    // Fire action after parsing
    do_action( 'ce_after_parsing', $created_posts );

    return $created_posts;
}

/**
 * Detect the content type of the response body
 *
 * @param string $body The response body
 * @return string Content type: 'json', 'xml', 'rss', 'html', or 'unknown'
 */
function ce_detect_content_type( $body ) {
    $body_trimmed = trim( $body );
    
    // Check for JSON
    if ( ( $body_trimmed[0] === '{' || $body_trimmed[0] === '[' ) && json_decode( $body_trimmed ) !== null ) {
        return 'json';
    }
    
    // Check for XML/RSS
    if ( strpos( $body_trimmed, '<?xml' ) === 0 || strpos( $body_trimmed, '<rss' ) === 0 || strpos( $body_trimmed, '<feed' ) === 0 ) {
        if ( stripos( $body_trimmed, '<rss' ) !== false || stripos( $body_trimmed, '<channel' ) !== false ) {
            return 'rss';
        }
        return 'xml';
    }
    
    // Check for HTML
    if ( stripos( $body_trimmed, '<html' ) !== false || stripos( $body_trimmed, '<!DOCTYPE' ) !== false ) {
        return 'html';
    }
    
    return 'unknown';
}

/**
 * Parse JSON content and create WordPress posts
 *
 * @param string $body JSON response body
 * @return array Array of created post IDs
 */
function ce_parse_json_content( $body ) {
    $data = json_decode( $body, true );
    $created_posts = array();
    
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        ce_log_error( 'Content Extractor: JSON decode error: ' . json_last_error_msg() );
        return array();
    }
    
    if ( empty( $data ) ) {
        return array();
    }
    
    // Handle different JSON structures
    $posts = array();
    
    // Structure 1: Direct array of posts
    if ( isset( $data[0] ) && is_array( $data[0] ) ) {
        $posts = $data;
    }
    // Structure 2: Object with 'posts' key
    elseif ( isset( $data['posts'] ) && is_array( $data['posts'] ) ) {
        $posts = $data['posts'];
    }
    // Structure 3: Object with 'data' key
    elseif ( isset( $data['data'] ) && is_array( $data['data'] ) ) {
        $posts = $data['data'];
    }
    // Structure 4: Object with 'items' key
    elseif ( isset( $data['items'] ) && is_array( $data['items'] ) ) {
        $posts = $data['items'];
    }
    // Structure 5: Single post object
    elseif ( isset( $data['title'] ) || isset( $data['content'] ) ) {
        $posts = array( $data );
    }
    
    foreach ( $posts as $post_data ) {
        if ( ! is_array( $post_data ) ) {
            continue;
        }
        
        $post_id = ce_create_post_from_data( $post_data );
        if ( $post_id ) {
            $created_posts[] = $post_id;
        }
    }
    
    return $created_posts;
}

/**
 * Parse XML/RSS content and create WordPress posts
 *
 * @param string $body XML/RSS response body
 * @return array Array of created post IDs
 */
function ce_parse_xml_content( $body ) {
    $created_posts = array();
    
    // Suppress warnings for malformed XML
    libxml_use_internal_errors( true );
    
    $dom = new DOMDocument();
    $loaded = @$dom->loadXML( $body );
    
    if ( ! $loaded ) {
        // Try loading as HTML if XML fails
        $loaded = @$dom->loadHTML( $body );
    }
    
    if ( ! $loaded ) {
        $errors = libxml_get_errors();
        ce_log_error( 'Content Extractor: XML parse error: ' . print_r( $errors, true ) );
        libxml_clear_errors();
        return array();
    }
    
    libxml_clear_errors();
    
    $xpath = new DOMXPath( $dom );
    
    // Try RSS/Atom feed structure
    $items = $xpath->query( '//item | //entry' );
    
    if ( $items->length === 0 ) {
        // Try custom XML structure
        $items = $xpath->query( '//post | //article | //content' );
    }
    
    foreach ( $items as $item ) {
        $post_data = array();
        
        // Extract title
        $title_nodes = $xpath->query( './/title', $item );
        if ( $title_nodes->length > 0 ) {
            $post_data['title'] = $title_nodes->item(0)->nodeValue;
        }
        
        // Extract content/description
        $content_nodes = $xpath->query( './/description | .//content | .//content:encoded | .//summary', $item );
        if ( $content_nodes->length > 0 ) {
            $post_data['content'] = $content_nodes->item(0)->nodeValue;
        }
        
        // Extract link
        $link_nodes = $xpath->query( './/link', $item );
        if ( $link_nodes->length > 0 ) {
            $post_data['link'] = $link_nodes->item(0)->nodeValue;
        }
        
        // Extract pubDate/date
        $date_nodes = $xpath->query( './/pubDate | .//published | .//updated | .//date', $item );
        if ( $date_nodes->length > 0 ) {
            $post_data['date'] = $date_nodes->item(0)->nodeValue;
        }
        
        // Extract categories/tags
        $category_nodes = $xpath->query( './/category | .//tag', $item );
        $categories = array();
        foreach ( $category_nodes as $cat_node ) {
            $categories[] = $cat_node->nodeValue;
        }
        if ( ! empty( $categories ) ) {
            $post_data['categories'] = $categories;
        }
        
        // Extract image/enclosure
        $image_nodes = $xpath->query( './/enclosure[@type="image"] | .//media:content | .//image', $item );
        if ( $image_nodes->length > 0 ) {
            $image_url = $image_nodes->item(0)->getAttribute( 'url' );
            if ( empty( $image_url ) ) {
                $image_url = $image_nodes->item(0)->getAttribute( 'href' );
            }
            if ( ! empty( $image_url ) ) {
                $post_data['image'] = $image_url;
            }
        }
        
        if ( ! empty( $post_data['title'] ) || ! empty( $post_data['content'] ) ) {
            $post_id = ce_create_post_from_data( $post_data );
            if ( $post_id ) {
                $created_posts[] = $post_id;
            }
        }
    }
    
    return $created_posts;
}

/**
 * Parse HTML content and create WordPress posts
 *
 * @param string $body HTML response body
 * @return array Array of created post IDs
 */
function ce_parse_html_content( $body ) {
    $created_posts = array();
    
    libxml_use_internal_errors( true );
    
    $dom = new DOMDocument();
    @$dom->loadHTML( mb_convert_encoding( $body, 'HTML-ENTITIES', 'UTF-8' ) );
    
    libxml_clear_errors();
    
    $xpath = new DOMXPath( $dom );
    
    // Try common HTML structures for blog posts/articles
    $articles = $xpath->query( '//article | //div[@class="post"] | //div[@class="article"] | //div[@class="entry"]' );
    
    if ( $articles->length === 0 ) {
        // Fallback: try to find any content containers
        $articles = $xpath->query( '//div[contains(@class, "content")] | //div[contains(@class, "post")]' );
    }
    
    foreach ( $articles as $article ) {
        $post_data = array();
        
        // Extract title (h1, h2, or .title)
        $title_nodes = $xpath->query( './/h1 | .//h2 | .//*[contains(@class, "title")]', $article );
        if ( $title_nodes->length > 0 ) {
            $post_data['title'] = trim( $title_nodes->item(0)->nodeValue );
        }
        
        // Extract content
        $content_nodes = $xpath->query( './/div[contains(@class, "content")] | .//div[contains(@class, "body")] | .//div[contains(@class, "entry-content")] | .//p', $article );
        $content_parts = array();
        foreach ( $content_nodes as $content_node ) {
            $content_parts[] = $dom->saveHTML( $content_node );
        }
        if ( ! empty( $content_parts ) ) {
            $post_data['content'] = implode( "\n", $content_parts );
        }
        
        // Extract image
        $image_nodes = $xpath->query( './/img', $article );
        if ( $image_nodes->length > 0 ) {
            $image_url = $image_nodes->item(0)->getAttribute( 'src' );
            if ( ! empty( $image_url ) ) {
                // Convert relative URLs to absolute if needed
                if ( strpos( $image_url, 'http' ) !== 0 ) {
                    $options = get_option( 'ce_settings' );
                    $base_url = $options['ce_url'] ?? '';
                    if ( ! empty( $base_url ) ) {
                        $parsed = parse_url( $base_url );
                        $image_url = $parsed['scheme'] . '://' . $parsed['host'] . $image_url;
                    }
                }
                $post_data['image'] = $image_url;
            }
        }
        
        if ( ! empty( $post_data['title'] ) || ! empty( $post_data['content'] ) ) {
            $post_id = ce_create_post_from_data( $post_data );
            if ( $post_id ) {
                $created_posts[] = $post_id;
            }
        }
    }
    
    return $created_posts;
}

/**
 * Create a WordPress post from parsed data
 *
 * @param array $post_data Post data array
 * @return int|false Post ID on success, false on failure
 */
function ce_create_post_from_data( $post_data ) {
    if ( empty( $post_data ) || ! is_array( $post_data ) ) {
        return false;
    }
    
    // Filter post data before processing
    $post_data = apply_filters( 'ce_post_data', $post_data );
    
    // Extract and sanitize title
    $title = '';
    if ( isset( $post_data['title'] ) ) {
        $title = wp_strip_all_tags( $post_data['title'] );
        $title = sanitize_text_field( $title );
    }
    
    if ( empty( $title ) ) {
        // Generate title from content if missing
        if ( ! empty( $post_data['content'] ) ) {
            $content_stripped = wp_strip_all_tags( $post_data['content'] );
            $title = wp_trim_words( $content_stripped, 8, '...' );
        } else {
            ce_log_error( 'Content Extractor: Post skipped - no title or content' );
            return false;
        }
    }
    
    // Check for duplicate posts
    $existing_post = get_page_by_title( $title, OBJECT, 'post' );
    if ( $existing_post ) {
        ce_log_error( 'Content Extractor: Post already exists: ' . $title );
        return false;
    }
    
    // Extract and sanitize content
    $content = '';
    if ( isset( $post_data['content'] ) ) {
        $content = wp_kses_post( $post_data['content'] );
    }
    
    if ( empty( $content ) && empty( $title ) ) {
        return false;
    }
    
    // Extract date
    $post_date = current_time( 'mysql' );
    if ( isset( $post_data['date'] ) ) {
        $parsed_date = strtotime( $post_data['date'] );
        if ( $parsed_date !== false ) {
            $post_date = date( 'Y-m-d H:i:s', $parsed_date );
        }
    }
    
    // Get default author (can be filtered)
    $author_id = apply_filters( 'ce_post_author', 1, $post_data );
    
    // Prepare post array
    $new_post = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => apply_filters( 'ce_post_status', 'publish', $post_data ),
        'post_author'  => $author_id,
        'post_date'    => $post_date,
    );
    
    // Add categories if provided
    if ( ! empty( $post_data['categories'] ) ) {
        $category_ids = ce_process_categories( $post_data['categories'] );
        if ( ! empty( $category_ids ) ) {
            $new_post['post_category'] = $category_ids;
        }
    }
    
    // Add tags if provided
    if ( ! empty( $post_data['tags'] ) ) {
        $tags = is_array( $post_data['tags'] ) ? $post_data['tags'] : explode( ',', $post_data['tags'] );
        $new_post['tags_input'] = array_map( 'trim', $tags );
    }
    
    // Fire action before post creation
    do_action( 'ce_before_post_create', $new_post, $post_data );
    
    // Insert post
    $post_id = wp_insert_post( $new_post, true );
    
    if ( is_wp_error( $post_id ) ) {
        ce_log_error( 'Content Extractor: Failed to create post: ' . $post_id->get_error_message() );
        return false;
    }
    
    if ( ! $post_id ) {
        ce_log_error( 'Content Extractor: wp_insert_post returned false for: ' . $title );
        return false;
    }
    
    // Set featured image if provided
    if ( ! empty( $post_data['image'] ) ) {
        $image_set = ce_set_featured_image( $post_id, $post_data['image'] );
        if ( ! $image_set ) {
            ce_log_error( 'Content Extractor: Failed to set featured image for post: ' . $post_id );
        }
    }
    
    // Fire action after post creation
    do_action( 'ce_after_post_create', $post_id, $post_data );
    
    return $post_id;
}

/**
 * Process and create categories from array of category names
 *
 * @param array $categories Array of category names
 * @return array Array of category IDs
 */
function ce_process_categories( $categories ) {
    if ( ! is_array( $categories ) ) {
        $categories = explode( ',', $categories );
    }
    
    $category_ids = array();
    
    foreach ( $categories as $category_name ) {
        $category_name = trim( $category_name );
        if ( empty( $category_name ) ) {
            continue;
        }
        
        // Check if category exists
        $term = get_term_by( 'name', $category_name, 'category' );
        
        if ( $term ) {
            $category_ids[] = $term->term_id;
        } else {
            // Create new category
            $new_term = wp_insert_term( $category_name, 'category' );
            if ( ! is_wp_error( $new_term ) ) {
                $category_ids[] = $new_term['term_id'];
            }
        }
    }
    
    return $category_ids;
}

/**
 * Set featured image for a post from URL
 *
 * @param int    $post_id   WordPress post ID
 * @param string $image_url URL of the image to download
 * @return bool True on success, false on failure
 */
function ce_set_featured_image( $post_id, $image_url ) {
    if ( empty( $image_url ) || empty( $post_id ) ) {
        return false;
    }
    
    // Filter image URL
    $image_url = apply_filters( 'ce_featured_image_url', $image_url, $post_id );
    
    $upload_dir = wp_upload_dir();
    
    if ( $upload_dir['error'] ) {
        ce_log_error( 'Content Extractor: Upload directory error: ' . $upload_dir['error'] );
        return false;
    }
    
    // Use wp_remote_get for remote URLs instead of file_get_contents
    $response = wp_remote_get( $image_url, array(
        'timeout' => 30,
        'sslverify' => false, // Some sites have SSL issues
    ) );
    
    if ( is_wp_error( $response ) ) {
        ce_log_error( 'Content Extractor: Failed to fetch image: ' . $response->get_error_message() );
        return false;
    }
    
    $image_data = wp_remote_retrieve_body( $response );
    if ( empty( $image_data ) ) {
        ce_log_error( 'Content Extractor: Empty image data received' );
        return false;
    }
    
    // Get file extension from URL or content type
    $content_type = wp_remote_retrieve_header( $response, 'content-type' );
    $file_ext = '';
    
    if ( $content_type ) {
        if ( strpos( $content_type, 'image/jpeg' ) !== false || strpos( $content_type, 'image/jpg' ) !== false ) {
            $file_ext = '.jpg';
        } elseif ( strpos( $content_type, 'image/png' ) !== false ) {
            $file_ext = '.png';
        } elseif ( strpos( $content_type, 'image/gif' ) !== false ) {
            $file_ext = '.gif';
        } elseif ( strpos( $content_type, 'image/webp' ) !== false ) {
            $file_ext = '.webp';
        }
    }
    
    // Fallback to URL extension
    if ( empty( $file_ext ) ) {
        $parsed_url = parse_url( $image_url );
        $path_info = pathinfo( $parsed_url['path'] ?? '' );
        $file_ext = isset( $path_info['extension'] ) ? '.' . $path_info['extension'] : '.jpg';
    }
    
    $filename = sanitize_file_name( basename( $image_url, $file_ext ) ) . '-' . time() . $file_ext;
    
    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }
    
    $file_written = file_put_contents( $file, $image_data );
    if ( false === $file_written ) {
        ce_log_error( 'Content Extractor: Failed to write image file: ' . $file );
        return false;
    }
    
    $wp_filetype = wp_check_filetype( $filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    );
    
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    if ( is_wp_error( $attach_id ) ) {
        ce_log_error( 'Content Extractor: Failed to insert attachment: ' . $attach_id->get_error_message() );
        @unlink( $file ); // Clean up
        return false;
    }
    
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    
    $result = set_post_thumbnail( $post_id, $attach_id );
    
    if ( $result ) {
        do_action( 'ce_featured_image_set', $post_id, $attach_id, $image_url );
    }
    
    return $result;
}
