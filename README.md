# WP Content Extractor

[![License](https://img.shields.io/github/license/VoxHash/WPContent-extractor)](LICENSE)
[![Release](https://img.shields.io/github/v/release/VoxHash/WPContent-extractor?sort=semver)](https://github.com/VoxHash/WPContent-extractor/releases)
[![Docs](https://img.shields.io/badge/docs-website-blue)](./docs/index.md)

> WordPress plugin that automatically extracts content from external URLs and publishes posts via scheduled cron jobs. Perfect for content creators who need to aggregate and republish content from multiple sources.

## ✨ Features

- **Automated Content Extraction**: Fetches content from specified URLs on a configurable schedule
- **Batch Processing**: Processes multiple posts per batch to avoid server overload
- **WordPress Integration**: Seamlessly publishes extracted content as WordPress posts
- **Featured Image Support**: Automatically downloads and sets featured images for posts
- **Admin Settings Page**: Easy-to-use settings interface in WordPress admin
- **Error Handling**: Comprehensive error logging and handling
- **Security First**: Uses WordPress best practices for remote requests and data sanitization

## 🧭 Table of Contents

- [Quick Start](#-quick-start)
- [Installation](#-installation)
- [Usage](#-usage)
- [Configuration](#-configuration)
- [Examples](#-examples)
- [Architecture](#-architecture)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)

## 🚀 Quick Start

```bash
# 1) Download the plugin
git clone https://github.com/VoxHash/WPContent-extractor.git
cd WPContent-extractor

# 2) Copy to WordPress plugins directory
cp -r . /path/to/wordpress/wp-content/plugins/wp-content-extractor

# 3) Activate in WordPress admin
# Navigate to Plugins → Installed Plugins → Activate "Content Extractor"
```

## 💿 Installation

See [docs/installation.md](docs/installation.md) for detailed installation steps.

1. Download or clone this repository
2. Copy the plugin files to your WordPress `wp-content/plugins/` directory
3. Activate the plugin through the WordPress admin panel
4. Navigate to Settings → Content Extractor to configure

## 🛠 Usage

Basic usage here. Advanced usage in [docs/usage.md](docs/usage.md).

1. **Configure Source URL**: Go to Settings → Content Extractor and enter your source URL
2. **Automatic Processing**: The plugin runs every 30 minutes via WordPress cron
3. **Manual Trigger**: You can trigger extraction manually by calling `ce_extract_content()` function

The plugin automatically:
- Fetches content from the configured URL
- Parses and extracts post data
- Publishes posts to WordPress
- Downloads and sets featured images
- Handles pagination for large content sets

## ⚙️ Configuration

| Setting | Description | Default |
|---|---|---|
| Source URL | The URL to extract content from | - |
| Posts per batch | Number of posts processed per run | 5 |
| Cron interval | How often to run extraction | Every 30 minutes |

Full configuration reference: [docs/configuration.md](docs/configuration.md)

## 📚 Examples

- Start here: [docs/examples/example-01.md](docs/examples/example-01.md)
- More: [docs/examples/](docs/examples/)

## 🧩 Architecture

High-level overview:

- **Main Plugin File** (`content-extractor.php`): Handles plugin initialization, settings, and cron scheduling
- **Functions File** (`functions.php`): Contains content parsing and post publishing logic
- **WordPress Cron**: Runs extraction every 30 minutes automatically
- **Settings API**: Uses WordPress Settings API for secure configuration

See [docs/architecture.md](docs/architecture.md) for detailed architecture documentation.

## 🗺 Roadmap

Planned milestones live in [ROADMAP.md](ROADMAP.md). For changes, see [CHANGELOG.md](CHANGELOG.md).

## 🤝 Contributing

We welcome PRs! Please read [CONTRIBUTING.md](CONTRIBUTING.md) and follow the PR template.

## 🔒 Security

Please report vulnerabilities via [SECURITY.md](SECURITY.md).

## 📄 License

This project is licensed under the MIT License - see [LICENSE](LICENSE) file for details.
