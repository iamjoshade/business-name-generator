# Business Name Generator UK

![Tests](https://github.com/YOUR-USERNAME/YOUR-REPO/actions/workflows/tests.yml/badge.svg)

A small Laravel app that turns a few keywords and an industry into business name ideas, tagline suggestions and a matching brand palette with font pairings.


This is a stripped-down, standalone version of a tool that runs in production on [Built For Small Business](https://builtforsmallbusiness.com), a free business management platform for UK sole traders and small companies. The production version also checks domain and social handle availability and stores searches. This one keeps only the offline logic, so it has no database, no API keys and no external calls.

## What it does

- Builds names from your keywords using four patterns: keyword and suffix, prefix and keyword, industry-branded prefix and suffix, and keyword pairs.
- Returns up to 15 unique names per search.
- Suggests five taglines for the chosen industry.
- Shows a five-colour brand palette with heading and body fonts.
- Covers ten industries, with a default fallback.

## Stack

Laravel 12, PHP 8.3+, Blade and Bootstrap 5.3 from a CDN. There is no build step.

## Run it locally

```bash
git clone https://github.com/YOUR-USERNAME/YOUR-REPO.git
cd YOUR-REPO
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

## Run the tests

```bash
php artisan test
```

The same command runs on every push through GitHub Actions, on PHP 8.3 and 8.4.

## What is tested

**Unit tests** (`tests/Unit/BusinessNameServiceTest.php`)

- Each name pattern produces the expected names, checked against the unshuffled candidate pool.
- Keywords are cleaned: punctuation, digits and one-letter words are dropped.
- Empty keywords, unknown industries and mixed-case industries fall back or normalise correctly.
- Output is capped at 15 names and never contains duplicates.
- Randomness is injected, so a seeded run gives the same names every time.
- Every industry has a complete palette with valid hex colours, plus taglines.

**Feature tests** (`tests/Feature/NameGeneratorTest.php`)

- The form loads with no results showing, and the dropdown is built from the service.
- A valid submission renders names, taglines and the palette.
- Every industry can be submitted.
- Empty keywords, keywords with no letters, over-long keywords, a missing industry and an unknown industry are all rejected.
- Entered keywords are kept when validation fails.
- Markup typed into the form is escaped in the response.

## Structure

```
app/Services/BusinessNameService.php        name, palette and tagline logic
app/Http/Requests/GenerateNamesRequest.php  validation
app/Http/Controllers/NameGeneratorController.php
resources/views/generator.blade.php
tests/Unit/BusinessNameServiceTest.php
tests/Feature/NameGeneratorTest.php
```
