# Getting Started

## Prerequisites

- WordPress 5.0 or higher
- PHP 7.4 or higher
- FTP/SFTP access to your WordPress installation (for manual installation)
- Admin access to WordPress dashboard

## Setup

1. **Download the Plugin**
   ```bash
   git clone https://github.com/VoxHash/WPContent-extractor.git
   ```

2. **Install to WordPress**
   - Copy the plugin folder to `wp-content/plugins/wp-content-extractor/`
   - Or use WordPress admin: Plugins → Add New → Upload Plugin

3. **Activate**
   - Go to Plugins → Installed Plugins
   - Find "Content Extractor" and click "Activate"

4. **Configure**
   - Navigate to Settings → Content Extractor
   - Enter your source URL
   - Save settings

5. **Verify**
   - The plugin will automatically run every 30 minutes via WordPress cron
   - Check WordPress posts to verify content extraction

## Next Steps

- See [Quick Start](quick-start.md) for a minimal setup
- Read [Usage Guide](usage.md) for detailed usage instructions
- Check [Configuration](configuration.md) for all available options
