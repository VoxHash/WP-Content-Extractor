# Quick Start

Get WP Content Extractor running in minutes.

## Minimal Setup

1. **Install the plugin** to your WordPress `wp-content/plugins/` directory

2. **Activate** via WordPress admin: Plugins → Installed Plugins → Activate "Content Extractor"

3. **Configure** the source URL:
   - Go to Settings → Content Extractor
   - Enter your content source URL (e.g., `https://example.com/api/posts`)
   - Click "Save Changes"

4. **Done!** The plugin will automatically extract and publish content every 30 minutes.

## Manual Trigger (Optional)

If you want to test immediately without waiting for cron:

```php
// Add this to your theme's functions.php or a custom plugin
if (function_exists('ce_extract_content')) {
    ce_extract_content();
}
```

## What Happens Next?

- Content is fetched from your configured URL
- Posts are parsed and published to WordPress
- Featured images are downloaded and attached
- Process repeats every 30 minutes automatically

For more details, see [Installation](installation.md) and [Usage](usage.md).
