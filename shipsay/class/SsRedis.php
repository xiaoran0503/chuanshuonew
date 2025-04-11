<?php class SsRedis extends Redis
{
	public function __construct($redisarr)
	{
		try
		{
			$this->connect($redisarr['host'],$redisarr['port']);
		}
		catch(\Throwable $th)
		{
			die('连接服务器Redis出错: '.$th);
		}
		$this->auth($redisarr['pass']);
		$this->select(empty($redisarr['db'])?0:intval($redisarr['db']));
	}
	public function ss_get($key)
	{
		global $site_url;
		return json_decode($this->get(md5($site_url.$key)),true);
	}
	public function ss_setex($key,$cache_time,$value)
	{
		global $site_url;
		return $this->setex(md5($site_url.$key),$cache_time,json_encode($value,JSON_UNESCAPED_UNICODE));
	}
	public function ss_redis_getrows($sql,$cache_time,$shuffle=false)
	{
		if($this->ss_get($sql))
		{
			return $this->ss_get($sql);
		}
		else
		{
			global $dbarr;
			$db=new Db($dbarr);
			$ret=$db->ss_getrows($sql);
			if($shuffle&&is_array($ret))shuffle($ret);
			$this->ss_setex($sql,$cache_time,$ret);
			return $ret;
		}
	}
	public function ss_flushDb()
	{
		global $use_redis;
		if($use_redis)
		{
			if($this->flushDb())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		return false;
	}
}
?>