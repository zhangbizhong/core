<?php

namespace Core\Database\Mysql;

use One\Facades\Cache;

class CacheBuild extends Build
{
    private $cache_time = 0;

    /**
     * 缓存时间(秒)
     * @param int $time
     */
    public function cache($time)
    {
        $this->cache_time = $time;
        return $this;
    }

    private $cache_tag = [];

    protected function get($sql = '', $build = [], $all = false)
    {
        if ($this->cache_time == 0) {
            return parent::get($sql, $build, $all);
        }
        return unserialize(Cache::get($this->getCacheKey($sql), function () use ($sql, $build, $all) {
            return serialize(parent::get($sql, $build, $all));
        }, $this->cache_time, $this->cache_tag));
    }

    public function exec($sql, array $build = [], $is_insert = false)
    {
        $ret = parent::exec($sql, $build, $is_insert);
        $this->flushCache();
        return $ret;
    }

    public function update($data)
    {
        $ret = parent::update($data);
        if ($this->isIgnoreColumn($data) === false) {
            $this->flushCache($data);
        }
        return $ret;
    }

    public function delete()
    {
        $ret = parent::delete();
        $this->flushCache();
        return $ret;
    }

    public function insert($data, $is_mulit = false)
    {
        $ret = parent::insert($data, $is_mulit);
        $this->flushCache([$this->getPriKey() => $ret] + $data);
        return $ret;
    }

    public function join($table, $first, $second = null, $type = 'inner')
    {
        $this->cache_tag[] = 'join+' . $table;
        return parent::join($table, $first, $second, $type);
    }


    private $columns = [];

    public function cacheColumn($columns)
    {
        sort($columns, SORT_STRING);
        $this->columns = $columns;
    }

    private $ignore_column = [];

    public function ignoreColumn($columns)
    {
        $this->ignore_column = $columns;
    }

    private function getCacheColumnValue($data = [])
    {
        if ($this->columns) {
            $w = [];
            foreach ($this->where as $v) {
                if (trim($v[1]) == '=') {
                    $w[$v[0]] = $v[2];
                }
            }
            $data = $data + $w;

            $keys = [];
            foreach ($this->columns as $f) {
                if (isset($data[$f])) {
                    $keys[] = $f . '_' . $data[$f];
                }
            }
            if ($keys) {
                return '-' . implode('+', $keys);
            }
        }
        return '';
    }

    private function getCacheKey($str = '')
    {
        $table = $this->from;
        $key   = $this->getCacheColumnValue();
        $hash  = sha1($str . $this->getSelectSql() . json_encode($this->build));
        return "DB#{$table}{$key}#{$hash}";
    }

    private function flushCache($data = [])
    {
        if ($this->cache_time > 0) {
            $table = $this->from;
            $key   = $this->getCacheColumnValue($data);
            Cache::delRegex("*#{$table}{$key}#*");
            Cache::flush('join+' . $table);
        }
    }

    private function isIgnoreColumn($data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, $this->ignore_column)) {
                return false;
            }
        }
        return true;
    }
}