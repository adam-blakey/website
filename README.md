# Adam Blakey's website

[![Build, Test & Deploy](https://github.com/adam-blakey/website/actions/workflows/deploy.yml/badge.svg?branch=main)](https://github.com/adam-blakey/website/actions/workflows/deploy.yml)
[![Conventional Commits](https://github.com/adam-blakey/website/actions/workflows/conventional-commits.yml/badge.svg)](https://github.com/adam-blakey/website/actions/workflows/conventional-commits.yml)
[![Latest release](https://img.shields.io/github/v/release/adam-blakey/website)](https://github.com/adam-blakey/website/releases)

![PHP 8.5](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Tailwind CSS 4](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?logo=tailwindcss&logoColor=white)
![Vite 8](https://img.shields.io/badge/Vite-8-646CFF?logo=vite&logoColor=white)
![Pest](https://img.shields.io/badge/tested_with-Pest-F28D1A)
![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=white)
![Cloudflare](https://img.shields.io/badge/Cloudflare-F38020?logo=cloudflare&logoColor=white)

My personal website, styled like a code editor: pages are "files" in a project tree and open as tabs.

![Home page](.github/screenshots/home.png)

| Interests | Editor | Mobile |
| --- | --- | --- |
| ![Music page](.github/screenshots/music.png) | ![Markdown editor](.github/screenshots/editor.png) | ![Home page on mobile](.github/screenshots/mobile.png) |

## Features

- **Blog** imported from WordPress, with categories, tags, responsive images and embedded video.
- **Now playing** from Last.fm or ListenBrainz, and **programming time** from Wakapi.
- **Upcoming concerts**, awards and talks.
- **Contact form** with reCAPTCHA v3, rate limiting and confirmation emails.
- **Markdown editor** (CodeMirror) at `/editor`.
- Sitemap at `/sitemap.xml`.

## Running locally

Needs PHP 8.5, Composer and Node.

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan images:resize
npm run build
php artisan serve
```

Set `LARAVEL_CLOUDFLARE_ENABLED=false` in `.env` if the machine can't reach Cloudflare's IP list. API keys for Last.fm, ListenBrainz, Wakapi and the WordPress import go in `.env`; see `.env.example`.

### Scheduled commands

`routes/console.php` runs these via `php artisan schedule:run`:

| Command | Runs |
| --- | --- |
| `music:fetch`, `programming-time:fetch`, `app:send-contact-confirmation-emails` | every minute |
| `blog:import-wordpress`, `blog:generate-responsive-images`, `concerts:import`, `music:clear-old` | hourly |
| `cloudflare:reload` | daily |

## Tests

```bash
php artisan test
```

## Deployment

[`deploy.yml`](.github/workflows/deploy.yml) builds and tests every push and pull request, then deploys over SSH:

- Pull requests deploy to staging.
- Merges to `main` deploy to staging, then to production after the environment's wait timer, and publish a GitHub release.

Commits follow [Conventional Commits](https://www.conventionalcommits.org); the release version bump is worked out from them. See the [PR template](.github/pull_request_template.md).
