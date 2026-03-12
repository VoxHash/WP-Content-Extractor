# CLI Usage

WP Content Extractor can be used via WP-CLI for manual operations.

## Manual Extraction

Trigger content extraction manually:

```bash
wp eval 'if (function_exists("ce_extract_content")) { ce_extract_content(); }'
```

## Check Cron Schedule

View scheduled cron events:

```bash
wp cron event list
```

Look for `ce_daily_event` in the list.

## Run Cron Manually

Trigger WordPress cron manually:

```bash
wp cron event run ce_daily_event
```

## Custom WP-CLI Command (Future)

A custom WP-CLI command could be added:

```bash
wp content-extractor extract
wp content-extractor status
wp content-extractor reset
```

This would require adding a custom WP-CLI command class to the plugin.
