
# SimpleM3UParser
PHP Simple M3U Playlist Parser, it clean, remove duplicate, and group the playlist.

## Usage
see `example.php`
```php
<?php
require_once  "M3UParser.php";

# open from file
$m3u = file_get_contents("playlist.m3u");

# open from url
# $m3u = 'http://localhost/play.m3u';

# open multiple url
/*
$m3u = array(
    'http://localhost/a.m3u',
    'http://localhost/b.m3u',
    'http://localhost/c.m3u',
);
*/

$parser = new  M3UParser($m3u);
echo  $parser->text();
```

## Available Functions
- `$parser->text()` return cleaned m3u
- `$parser->list()` return array of playlist group
- `$parser->getGroup()` return playlist group names
- `$parser->getDuplicateURL()` return duplicate URLs

**Example Dirty M3U Playlist**
```
#EXTM3U

#EXTINF:-1 tvg-logo="https://png" group-title="Sport", WWE UK
http://localhost/index.m3u8

#EXTINF:-1 group-title="Sport", WWE US ( no source http, will be removed)
#EXTINF:-1 tvg-logo="https://colorLogoPNG.png" group-title="Sports", MLB


http://localhost/mlb.m3u8

# ( Duplicate URL, will be removed)
#EXTINF:-1 tvg-logo="https://png" group-title="Sport", WWE UK
http://localhost/index.m3u8

#KODIPROP:inputstream.adaptive.license_type=com.widevine.alpha

#KODIPROP:inputstream.adaptive.license_key=https://localhost


#EXTINF:-1 group-title="Entertaiment" tvg-logo="http://tlc.png", TLC

https://localhost/dash.mpd
```

**Cleaned M3U Playlist**

```
#EXTM3U

#EXTINF:-1 tvg-logo="https://png" group-title="Sport", WWE UK
http://localhost/index.m3u8

#EXTINF:-1 tvg-logo="https://colorLogoPNG.png" group-title="Sports", MLB
http://localhost/mlb.m3u8

#KODIPROP:inputstream.adaptive.license_type=com.widevine.alpha
#KODIPROP:inputstream.adaptive.license_key=https://localhost
#EXTINF:-1 group-title="Entertaiment" tvg-logo="http://tlc.png", TLC
https://localhost/dash.mpd
```
