# Contributing to WP Content Extractor

Thanks for helping improve WP Content Extractor!

## Code of Conduct

Please read and follow our [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md).

## Development Setup

```bash
# Clone
git clone https://github.com/VoxHash/WPContent-extractor.git
cd WPContent-extractor

# Set up WordPress development environment
# Install WordPress locally or use a Docker setup
# Copy plugin to wp-content/plugins/wp-content-extractor

# Run PHP linting
php -l content-extractor.php
php -l functions.php
```

## Branching & Commit Style

- **Branches**: `feature/…`, `fix/…`, `docs/…`, `chore/…`
- **Conventional Commits**: `feat:`, `fix:`, `docs:`, `refactor:`, `test:`, `chore:`

Examples:
- `feat: add support for custom post types`
- `fix: resolve undefined variable in extraction function`
- `docs: update installation instructions`

## Pull Requests

- Link related issues
- Add tests if applicable
- Update documentation
- Follow the PR template
- Keep diffs focused and atomic
- Ensure PHP syntax is valid (`php -l`)

## Code Standards

- Follow WordPress Coding Standards
- Use WordPress functions for security (e.g., `wp_remote_get()`, `esc_url_raw()`, `sanitize_text_field()`)
- Add error handling for all external operations
- Document complex functions with PHPDoc comments
- Use meaningful variable and function names

## Testing

Before submitting a PR:

1. Test the plugin activation/deactivation
2. Verify cron scheduling works correctly
3. Test content extraction with a sample URL
4. Check error handling with invalid URLs
5. Verify settings page functionality

## Release Process

- Semantic Versioning (MAJOR.MINOR.PATCH)
- Update [CHANGELOG.md](CHANGELOG.md) with changes
- Tag releases with version number
- Update plugin header version in main file
