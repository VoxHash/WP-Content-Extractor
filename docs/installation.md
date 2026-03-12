# Installation

## Method 1: Manual Installation (Recommended)

1. **Download the Plugin**
   ```bash
   git clone https://github.com/VoxHash/WPContent-extractor.git
   cd WPContent-extractor
   ```

2. **Upload to WordPress**
   - Connect to your server via FTP/SFTP
   - Navigate to `wp-content/plugins/`
   - Upload the entire `WPContent-extractor` folder
   - Ensure folder structure: `wp-content/plugins/wp-content-extractor/content-extractor.php`

3. **Activate**
   - Log in to WordPress admin
   - Go to Plugins → Installed Plugins
   - Find "Content Extractor" and click "Activate"

## Method 2: WordPress Admin Upload

1. **Download ZIP**
   - Download the repository as ZIP
   - Or create a ZIP of the plugin folder

2. **Upload via Admin**
   - Go to Plugins → Add New
   - Click "Upload Plugin"
   - Choose the ZIP file
   - Click "Install Now"
   - Click "Activate Plugin"

## Method 3: WP-CLI

```bash
wp plugin install https://github.com/VoxHash/WPContent-extractor/archive/main.zip --activate
```

## Verification

After installation, verify:

1. Plugin appears in Plugins → Installed Plugins
2. Settings page exists at Settings → Content Extractor
3. No PHP errors in WordPress debug log

## Troubleshooting

- **Plugin not appearing**: Check file permissions and folder structure
- **Activation errors**: Check PHP version (requires 7.4+)
- **Cron not running**: Verify WordPress cron is enabled

See [Troubleshooting](troubleshooting.md) for more help.
