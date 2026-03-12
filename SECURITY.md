# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Reporting a Vulnerability

Please report security vulnerabilities to **security@voxhash.dev** with:

- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

We will respond within 48 hours and work with you to address the issue before public disclosure.

## Security Best Practices

When using this plugin:

- Keep WordPress and PHP updated
- Use HTTPS for all external URLs
- Regularly review extracted content
- Monitor WordPress debug logs
- Use strong authentication for WordPress admin
- Limit plugin access to trusted users only

## Known Security Considerations

- The plugin makes external HTTP requests - ensure source URLs are trusted
- Content is sanitized using WordPress functions, but parsing logic should be reviewed
- Featured images are downloaded from external sources - validate image URLs
- Cron jobs run with WordPress permissions - ensure proper user capabilities
