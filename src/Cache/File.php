<?php

namespace Core\Cache;

use One\ConfigTrait;

class File extends Cache
{
    use ConfigTrait;

    public function __construct()
    {
        $this->mkdir();
    }

    private function mkdir()
    {
        if (!is_dir(self::$conf['path'])) {
            mkdir(self::$conf['path'], 0755, true);
        }
    }

    protected function getTagKey($key, $tags = [])
    {
        if ($tags) {
            $prev = '';
            foreach ($tags as $tag) {
                $p = $this->get($tag);
                if (!$p) {
                    $p = $this->flush($tag);
                }
                $prev = md5($p . $prev);
            }
            return static::$conf['prefix'] . $key . '#tag_' . $prev;
        } else {
            return static::$conf['prefix'] . $key;
        }
    }


    public function get($key, \Closure $closure = null, $ttl = 315360000, $tags = [])
    {
        $k = $this->getTagKey($key, $tags);
        $f = $this->getFileName($k);
        if (file_exists($f)) {
            $str = file_get_contents($f);
            if ($str) {
                $time = substr($str, 0, 10);
                $str  = substr($str, 10);
                if ($time > time()) {
                    $this->gc();
                    return $str;
                } else {
                    $this->del($key);
                }
            }
        }
        if ($closure) {
            $val = $closure();
            $this->set($key, $val, $ttl, $tags);
            return $val;
        } else {
            $this->del($key);
            return false;
        }
    }

    private function gc()
    {
        $i = rand(1, 300);
        if ($i !== 8) {
            return true;
        }
        $fs = glob(self::$conf['path'] . '/*');
        foreach ($fs as $f) {
            $str = file_get_contents($f);
            if ($str) {
                $time = substr($str, 0, 10);
                if ($time < time()) {
                    @unlink($f);
                }
            }
        }
    }

    public function delRegex($key)
    {
        $this->del(glob($key));
    }

    public function flush($tag)
    {
        $id = md5(uuid());
        $this->set($tag, $id);
        return $id;
    }

    public function set($key, $val, $ttl = 315360000, $tags = [])
    {
        $key  = $this->getTagKey($key, $tags);
        $file = $this->getFileName($key);
        file_put_contents($file, (time() + $ttl) . $val);
    }

    public function del($key)
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                @unlink($this->getFileName($k));
            }
        } else {
            return @unlink($this->getFileName(self::$conf['prefix'] . $key));
        }
    }

    private function getFileName($key)
    {
        return self::$conf['path'] . '/' . $key;
    }

}