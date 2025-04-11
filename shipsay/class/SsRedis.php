<?php
class SsRedis extends Redis
{
    public function __construct(array $redisarr)
    {
        try {
            $this->connect($redisarr['host'], $redisarr['port']);
        } catch (RedisException $e) {
            die('连接服务器Redis出错: '. $e->getMessage());
        }
        if (!empty($redisarr['pass'])) {
            $this->auth($redisarr['pass']);
        }
        $this->select(isset($redisarr['db']) ? (int)$redisarr['db'] : 0);
    }

    public function ss_get(string $key): array|false
    {
        global $site_url;
        $cacheKey = md5($site_url . $key);
        $result = $this->get($cacheKey);
        return $result ? json_decode($result, true) : false;
    }

    public function ss_setex(string $key, int $cache_time, $value): bool
    {
        global $site_url;
        $cacheKey = md5($site_url . $key);
        return $this->setex($cacheKey, $cache_time, json_encode($value, JSON_UNESCAPED_UNICODE));
    }

    public function ss_redis_getrows(string $sql, int $cache_time, bool $shuffle = false): array|false
    {
        $cachedResult = $this->ss_get($sql);
        if ($cachedResult) {
            return $cachedResult;
        }

        global $dbarr;
        $db = new Db($dbarr);
        $ret = $db->ss_getrows($sql);

        if ($shuffle && is_array($ret)) {
            shuffle($ret);
        }

        if ($ret) {
            $this->ss_setex($sql, $cache_time, $ret);
        }
        return $ret;
    }

    public function ss_flushDb(): bool
    {
        global $use_redis;
        return $use_redis && $this->flushDb();
    }
}
?>