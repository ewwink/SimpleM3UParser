<?php
/***********************************************
 *
 * Simple M3U Playlist Parser 
 * https://github.com/ewwink/SimpleM3UParser
 *
 ***********************************************/

require_once "M3UParser.php";

$m3u = <<<EOF
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
EOF;

# open from file
# $m3u = file_get_contents("playlist.m3u");

# open from url
# $parser = new M3UParser('http://localhost/play.m3u');



# open multiple url
/*
$parser = new M3UParser(
    array(
        'http://localhost/a.m3u',
        'http://localhost/b.m3u',
        'http://localhost/c.m3u',
    )
);
*/

$parser = new M3UParser($m3u);

header('content-type: text/plain');

echo $parser->text();;
print_r($parser->list());
print_r($parser->getGroup());
print_r($parser->getDuplicateURL());
