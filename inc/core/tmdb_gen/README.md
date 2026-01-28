# 🎬 TMDB Generator - Complete Documentation

## Overview
TMDB Generator is a powerful WordPress plugin for Dooplay theme that allows bulk importing of movies and TV shows from TMDB with live animation progress.

## Features
✅ **Bulk Import** - Import multiple movies/TV shows at once
✅ **Live Animation** - Beautiful animated grid showing import progress
✅ **30+ Embed Servers** - Pre-configured with popular streaming servers
✅ **Complete Metadata** - All fields from TMDB (cast, crew, genres, images, etc.)
✅ **TV Show Support** - Full season & episode structure
✅ **Server Management** - Enable/disable specific servers
✅ **Search & Popular** - Search TMDB or get trending content

## Installation

1. Files are already loaded in `/inc/core/tmdb_gen/`
2. Plugin auto-loads from `functions.php`
3. Access from WordPress Admin: **TMDB Generator** menu

## Usage

### Bulk Import
1. Go to **TMDB Generator** → **Bulk Import**
2. Choose content type (Movies/TV Shows)
3. Search for content or click "Get Popular"
4. Select items you want to import
5. Click "Import Selected"
6. Watch the live animation as imports complete!

### Server Settings
1. Go to **TMDB Generator** → **Server Settings**
2. Enable/disable servers as needed
3. Click "Save Settings"

## File Structure
```
tmdb_gen/
├── init.php                    # Main initialization
├── classes/
│   ├── helpers.php            # Helper functions
│   ├── servers.php            # 30+ embed servers
│   ├── importer.php           # Import logic
│   ├── bulk_import.php        # Bulk processing
│   ├── ajax.php               # AJAX handlers
│   └── admin.php              # Admin interface
├── tpl/
│   ├── bulk_import.php        # Bulk import UI
│   └── server_settings.php    # Server settings UI
└── assets/
    ├── admin.js               # Frontend JavaScript
    └── admin.css              # Styling
```

## Available Servers
1. **Player Servers**: VidSrc, 2Embed, AutoEmbed, VidSrcXyz, etc.
2. **Download Servers**: All servers support download links
3. **Both**: Most servers work for both player and download

### Server URL Variables
- `{tmdb_id}` - TMDB ID
- `{imdb_id}` - IMDB ID (without "tt" prefix)
- `{season}` - Season number (TV shows)
- `{episode}` - Episode number (TV shows)

Example:
```
Movie: https://vidsrc.xyz/embed/movie/{tmdb_id}
TV Show: https://vidsrc.xyz/embed/tv/{tmdb_id}/{season}/{episode}
```

## Import Process

### Single Import Flow
1. Fetch data from TMDB API
2. Check if content already exists
3. Create WordPress post
4. Add metadata (idtmdb, ids, poster, backdrop, etc.)
5. Upload poster image
6. Create taxonomies (genres, cast, directors, year)
7. Add embed servers
8. Process seasons/episodes (for TV shows)
9. Return success response

### Bulk Import Flow
1. User selects multiple items
2. Items queued in JavaScript
3. Process one item every 1 second
4. Show live animation status
5. Update progress bar
6. Mark completed items with ✓ or ✗

## Animation States
- **Waiting...** - Item in queue (gray)
- **Importing...** - Currently processing (blue, pulsing)
- **✓ Success** - Import completed (green)
- **✗ Failed** - Import failed (red)

## AJAX Endpoints

### `tmdbgen_import_single`
Import single movie/TV show
- Parameters: `tmdb_id`, `type` (movie/tv)
- Returns: Success/error with post ID

### `tmdbgen_import_bulk`
Process bulk import (deprecated - now handled by JS queue)
- Parameters: `ids[]`, `type`
- Returns: Array of results

### `tmdbgen_get_popular`
Get popular/trending content
- Parameters: `type`, `page`
- Returns: Array of TMDB items

### `tmdbgen_search`
Search TMDB
- Parameters: `query`, `type`
- Returns: Search results

### `tmdbgen_save_servers`
Save server settings
- Parameters: `enabled_servers[]`
- Returns: Success message

## Post Meta Fields
All TMDB data is stored in post meta:

| Meta Key | Description |
|----------|-------------|
| `idtmdb` | TMDB ID |
| `ids` | IMDB ID |
| `dt_poster` | Poster URL |
| `dt_backdrop` | Backdrop URL |
| `imagenes` | Gallery images (serialized) |
| `youtube_id` | Trailer ID |
| `runtime` | Duration (minutes) |
| `dt_cast` | Cast names |
| `dt_dir` | Directors |
| `dt_creator` | Creators (TV shows) |
| `imdbRating` | Rating |
| `dtrate` | Average rating |
| `dt_vote_count` | Vote count |

## Taxonomies
- **genres** - Movie/TV genres
- **dtcast** - Cast members
- **dtdirector** - Directors
- **dtcreator** - Creators (TV shows)
- **dtyear** - Release year

## TV Show Structure
```
TV Show (Post Type: tvshows)
├── Season 1 (Post Type: seasons)
│   ├── Episode 1 (Post Type: episodes)
│   ├── Episode 2
│   └── ...
├── Season 2
│   └── ...
```

Each season/episode gets:
- Parent relationship
- Embed servers
- Metadata
- Air dates

## Troubleshooting

### Import Fails
- Check TMDB API key in `init.php`
- Verify internet connection
- Check WordPress error log

### No Servers Showing
- Go to Server Settings
- Enable desired servers
- Save settings

### Animation Not Working
- Check JavaScript console for errors
- Verify jQuery is loaded
- Clear browser cache

### Duplicate Imports
- Plugin checks for existing `idtmdb`
- If found, skips import
- Manual check: Search posts by TMDB ID

## Performance Tips
1. **Import Delay**: 1 second between imports prevents rate limiting
2. **Batch Size**: Import 20-50 items at a time
3. **Server Selection**: Disable unused servers for faster imports
4. **Image Upload**: Posters uploaded to WordPress media library
5. **Caching**: Results cached where possible

## API Limits
- TMDB API: 40 requests per 10 seconds
- Plugin uses 1 second delay to stay within limits
- For large imports, do in batches

## Customization

### Add New Server
Edit `classes/servers.php`:
```php
'server_name' => array(
    'name' => 'Server Name',
    'url' => 'https://example.com/embed/movie/{tmdb_id}',
    'url_tv' => 'https://example.com/embed/tv/{tmdb_id}/{season}/{episode}',
    'type' => 'both'  // player, download, or both
)
```

### Modify Import Data
Edit `classes/importer.php` → `import_movie()` or `import_tvshow()`

### Change Animation Style
Edit `assets/admin.css` → `.import-item` classes

### Adjust Import Delay
Edit `assets/admin.js` → `setTimeout(function(){ ... }, 1000);`
Change `1000` to milliseconds (1000 = 1 second)

## Support
For issues or questions:
1. Check WordPress debug log
2. Verify all files exist in `/inc/core/tmdb_gen/`
3. Test with small batch first
4. Check browser console for JS errors

## Credits
- TMDB API: https://www.themoviedb.org/
- Dooplay Theme: https://doothemes.com/
- Developer: TMDB Generator Team

## Version
Current Version: 1.0.0
