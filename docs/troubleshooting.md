# Troubleshooting

## Common Issues

### Plugin Not Activating

**Symptoms:** Plugin fails to activate or shows PHP errors

**Solutions:**
- Check PHP version (requires 7.4+)
- Verify file permissions (files should be readable)
- Check WordPress debug log for specific errors
- Ensure all required files are present

### Cron Not Running

**Symptoms:** Content is not being extracted automatically

**Solutions:**
- Verify cron is scheduled: `wp cron event list`
- Check if WordPress cron is enabled (some hosts disable it)
- Manually trigger: `wp cron event run ce_daily_event`
- Consider using a real cron job to trigger WordPress cron

### No Posts Being Created

**Symptoms:** Cron runs but no posts appear

**Solutions:**
- Check source URL is correct and accessible
- Verify content parsing function is implemented
- Check WordPress debug log for errors
- Test parsing function manually with sample data
- Verify API returns expected format

### Featured Images Not Downloading

**Symptoms:** Posts created but no featured images

**Solutions:**
- Verify image URLs are accessible
- Check file permissions on uploads directory
- Ensure `wp_remote_get()` can access external URLs
- Check for SSL certificate issues
- Verify image URLs are absolute (not relative)

### Memory or Timeout Errors

**Symptoms:** PHP memory limit or execution timeout errors

**Solutions:**
- Reduce `$posts_per_page` value
- Increase PHP memory limit: `ini_set('memory_limit', '256M')`
- Increase execution time: `set_time_limit(300)`
- Process posts in smaller batches

### Settings Not Saving

**Symptoms:** Settings page doesn't save values

**Solutions:**
- Check user has `manage_options` capability
- Verify nonce is present in form
- Check for JavaScript errors in browser console
- Verify settings callback function exists

## Debug Mode

Enable debug logging:

```php
// In wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'CE_DEBUG', true );
```

Check logs at: `wp-content/debug.log`

## Getting Help

- Check [FAQ](faq.md) for common questions
- Open an issue on GitHub with:
  - WordPress version
  - PHP version
  - Plugin version
  - Error messages/logs
  - Steps to reproduce
