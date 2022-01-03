<?php

/***********************************************
 *
 * Simple M3U Playlist Parser 
 * https://github.com/ewwink/SimpleM3UParser
 *
 ***********************************************/
class M3UParser
{
    private $result;
    private $duplicateURL = array();
    private $uniqueURL = array();

    function __construct($m3u)
    {
        $this->result = array(
            'text' => "#EXTM3U\n\n",
            'list' => array()
        );
        if (gettype($m3u) == 'array' or preg_match("#^https?://#", $m3u))
            $m3u = $this->downloadM3U($m3u);
        $this->parse($m3u);
    }

    function downloadM3U($src)
    {
        $data = '';
        if (gettype($src) == 'string')
            $src = array($src);
        foreach ($src as $link) {
            $data .= @file_get_contents($link);
        }
        return $data;
    }
    private function startsWith($str, $find)
    {
        return strpos($str, $find) === 0;
    }
    private function constains($str, $find)
    {
        return strpos($str, $find) !== false;
    }
    private function resetItem()
    {
        return array(
            'extinf' => false,
            'lines' => '',
            'group' => 'nogroup'
        );
    }
    private function getinfo($extinf)
    {
        preg_match("/group-title=['\"](.+?)['\"]/", $extinf, $group);
        $groupName = 'nogroup';
        if (count($group) > 0) {
            $groupName = strtoupper(trim($group[1]));
        }
        return $groupName;
    }

    function getDuplicateURL()
    {
        return $this->duplicateURL;
    }
    function getGroup()
    {
        return array_keys($this->result['list']);
    }
    function text()
    {
        return $this->result['text'];
    }
    function list()
    {
        return $this->result['list'];
    }
    private function parse($source)
    {
        $item = $this->resetItem();
        $start = false;
        $lines = explode("\n", $source);
        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) continue;

            if (!$start) {
                if ($this->startsWith($line, '#EXTINF') or $this->startsWith($line, '#KODIPROP')) {
                    $start = true;
                    if ($this->startsWith($line, '#EXTINF')) {
                        $item['extinf'] = true;
                        $item['group'] = $this->getinfo($line);
                    }
                    $item['lines'] .= $line . "\n";
                }
            } else {
                if ($this->startsWith($line, '#EXTINF') and $item['extinf']) {
                    $item['lines'] = $line . "\n";
                    $item['group'] = $this->getinfo($line);
                } else if ($this->startsWith($line, '#EXTINF') and !$item['extinf']) {
                    $item['lines'] .= $line . "\n";
                    $item['group'] = $this->getinfo($line);
                } else if ($this->startsWith($line, '#KODIPROP') and $item['extinf']) {
                    $item['extinf'] = false;
                    $item['lines'] = $line . "\n";
                } else {
                    if ($this->startsWith($line, 'http')) {
                        $item['lines'] .= $line . "\n\n";
                        $item['extinf'] = false;
                        if (!isset($this->uniqueURL[$line])) {
                            $this->uniqueURL[$line] = '';
                            $this->result['text'] .= $item['lines'];
                            $group = $item['group'];
                            if (!isset($this->result['list'][$group])) {
                                $this->result['list'][$group] = array();
                            }
                            array_push($this->result['list'][$item['group']], $item['lines']);
                        } else {
                            array_push($this->duplicateURL, $line);
                        }
                        $item = $this->resetItem();
                        $start = false;
                    } else if ($this->startsWith($line, '#KODIPROP') or $this->startsWith($line, '#EXTVLCOPT')) {
                        $item['lines'] .= $line . "\n";
                    }
                }
            }
        }
    }
}
