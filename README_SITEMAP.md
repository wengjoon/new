# Laravel Sitemap Generator

This command-line tool generates XML sitemaps from a text file of URLs. It efficiently processes large URL lists and manages sitemap creation according to Google's sitemap protocol.

## Features

- Processes URLs from an input text file
- Tracks processed URLs to avoid duplication
- Generates multiple sitemap files with a maximum of 1000 URLs per file
- Creates a sitemap index file linking all individual sitemaps
- Updates robots.txt to reference the sitemap
- Provides detailed logging of the generation process

## Installation

The sitemap generator is already integrated into this Laravel application. No additional installation is required.

## Usage

Run the command with the following syntax:

```bash
php artisan sitemap:generate {input_file} [options]
```

### Arguments

- `input_file`: Path to the text file containing URLs (one URL per line)

### Options

- `--tracking_file=PATH`: Path to the file that tracks processed URLs (default: `storage/app/sitemap/tracking.txt`)
- `--output_dir=PATH`: Directory where sitemaps will be stored (default: `public/sitemaps`)
- `--urls_per_sitemap=NUMBER`: Maximum number of URLs per sitemap file (default: 1000)

### Example

```bash
php artisan sitemap:generate urls.txt --tracking_file=storage/app/sitemap/tracking.txt --output_dir=public/sitemaps --urls_per_sitemap=1000
```

## Input File Format

The input file should contain one URL per line, for example:

```
https://example.com/
https://example.com/page1
https://example.com/page2
https://example.com/page3
```

## Output

The command generates:

1. Multiple sitemap XML files in the output directory, each containing up to the specified number of URLs
2. A sitemap index file (`sitemap.xml`) that references all individual sitemap files
3. Updates to the robots.txt file to include the sitemap index and disallow individual sitemaps

## Tracking

The command maintains a tracking file that stores all processed URLs. This ensures that:

1. Only new URLs are added to sitemaps in subsequent runs
2. No duplicate URLs are processed
3. Previously generated sitemaps are properly managed

## Performance

The command is designed to efficiently handle large URL files by:

1. Processing input files line-by-line rather than loading the entire file into memory
2. Using efficient file I/O operations
3. Providing progress feedback for long-running operations

## Logging

Detailed logs are generated including:

- Total number of URLs processed
- Number of new URLs added
- Number of sitemap files generated
- Execution time

## Advanced Usage

### Processing Large URL Lists

For very large URL lists (millions of URLs), it's recommended to:

1. Split the input file into smaller chunks
2. Process each chunk separately
3. Use the tracking file to maintain state between runs

### Automation

You can schedule the sitemap generation using Laravel's task scheduler by adding the following to your `app/Console/Kernel.php` file:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('sitemap:generate /path/to/urls.txt')->daily();
}
```

This will automatically generate/update your sitemaps daily. 