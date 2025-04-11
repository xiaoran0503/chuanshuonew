<?php class Text
{
	static function strArr2p($strArr)
	{
		$ret='';
		foreach($strArr as $v)
		{
			$tmpvar=preg_replace('/　{2,}|\s{2,}/is',' ',$v);
			if(strlen($tmpvar)>1)
			{
				$ret.='<p>'.$tmpvar.'</p>';
			}
		}
		return $ret;
	}
	static function readpage_split($txt,$page=2,$param="\n")
	{
		$txt=htmlspecialchars($txt,ENT_NOQUOTES);
		$retarr=array();
		$all_lines=explode($param,$txt);
		$split_lines=ceil(count($all_lines)/$page);
		$tmparr=array_chunk($all_lines,$split_lines);
		for($i=0;$i<count($tmparr);
		$i++)
		{
			$retarr[$i]=self::strArr2p($tmparr[$i]);
		}
		return $retarr;
	}
	static function ss_txt2p($txt,$param="\n")
	{
		$arr=explode($param,$txt);
		return self::strArr2p($arr);
	}
	static function ss_txt2des($txt)
	{
		$ret=htmlspecialchars($txt,ENT_NOQUOTES);
		$ret=preg_replace('/　{2,}|\s{2,}/is',' ',$ret);
		$ret=mb_substr($ret,0,200,'UTF-8');
		return $ret;
	}
	static function ss_lastupdate($time)
	{
		global $is_ft;
		$ret='';
		$time=intval($time);
		if($time>time())$time=time();
		$diff=time()-$time;
		if($diff<2*60)
		{
			$ret='刚刚';
		}
		elseif($diff<60*60)
		{
			$ret=floor($diff/60).'分钟前';
		}
		elseif($diff<60*60*24)
		{
			$ret=floor($diff/(60*60)).'小时前';
		}
		elseif($diff<60*60*24*30)
		{
			$ret=floor($diff/(60*60*24)).'天前';
		}
		elseif($diff<60*60*24*365)
		{
			$ret=floor($diff/(60*60*24*30)).'个月前';
		}
		else
		{
			$ret=date('Y-m-d',$time);
		}
		if($is_ft)$ret=Convert::jt2ft($ret);
		return $ret;
	}
	static function ss_toutf8($str)
	{
		if(mb_detect_encoding($str,'UTF-8',true)===false)
		{
			$str=mb_convert_encoding($str,'UTF-8',['GBK','GB2312']);
		}
		return $str;
	}
	static function ss_get_contents($url)
	{
		if(function_exists('curl_init'))
		{
			$ci=curl_init();
			curl_setopt($ci,CURLOPT_URL,$url);
			curl_setopt($ci,CURLOPT_REFERER,$url);
			curl_setopt($ci,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ci,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ci,CURLOPT_CONNECTTIMEOUT,30);
			curl_setopt($ci,CURLOPT_TIMEOUT,60);
			curl_setopt($ci,CURLOPT_HEADER,0);
			curl_setopt($ci,CURLOPT_ENCODING,'gzip,deflate');
			$useragent=' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36';
			curl_setopt($ci,CURLOPT_USERAGENT,$useragent);
			$ret=curl_exec($ci);
			$err=curl_error($ci);
			if(false===$ret||!empty($err))
			{
				$ret=$err;
			}
			curl_close($ci);
		}
		else
		{
			$context=["ssl"=>["verify_peer"=>false,"verify_peer_name"=>false]];
			$stream_context=stream_context_create($context);
			$ret=@file_get_contents("compress.zlib://".$url,false,$stream_context);
		}
		return $ret;
	}
	static function ss_substr($str,$int=6)
	{
		return@mb_substr($str,0,$int,'UTF-8');
	}
	static function ss_filter($str,$filter_str)
	{
		function ss_str2preg($str)
		{
			$from=['\\$\\$\\$\\$','/','\\.','\'','"'];
			$to=['.*?','\\/','\\.','\'','\"'];
			$ret=preg_quote(trim($str));
			$ret=str_replace($from,$to,$ret);
			return $ret;
		}
		$filterlines=explode("\n",trim($filter_str));
		foreach($filterlines as $v)
		{
			$tmparr=explode("♂",$v);
			$from[]='/'.ss_str2preg($tmparr[0]).'/is';
			$to[]=$tmparr[1];
		}
		return preg_replace($from,$to,$str);
	}
}
?>