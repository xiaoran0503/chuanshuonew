<?php class Url
{
	static function get_img_url($aid,$imgflag)
	{
		global $remote_img_url;
		global $local_img;
		global $is_multiple;
		global $site_url;
		if($imgflag==9||$imgflag==1||$imgflag==75)
		{
			$remote_img=$remote_img_url.'/'.intval($aid/1000).'/'.$aid.'/'.$aid.'s.jpg';
			$newid=$is_multiple?ss_newid($aid):$aid;
			if($local_img==1)
			{
				$tmpstr='/images/'.intval($newid/1000).'/'.$newid;
				$local_dir=$_SERVER['DOCUMENT_ROOT'].$tmpstr;
				$local_file=$local_dir.'/'.$newid.'s.jpg';
				if(!is_file($local_file))
				{
					if(!is_dir($local_dir))mkdir($local_dir,0777,true);
					$temp_img=Text::ss_get_contents($remote_img);
					if(strlen($temp_img)<1)
					{
						$temp_img=Text::ss_get_contents(SELF::nocover_url());
					}
					@file_put_contents($local_file,$temp_img);
				}
				return $site_url.$tmpstr.'/'.$newid.'s.jpg';
			}
			elseif($local_img==2)
			{
				return $remote_img_url.'/'.intval($newid/1000).'/'.$newid.'/'.$newid.'s.jpg';
			}
			else
			{
				return $remote_img;
			}
		}
		else
		{
			return SELF::nocover_url();
		}
	}
	static function nocover_url()
	{
		global $theme_dir,$site_url;
		return $site_url.'/static/'.$theme_dir.'/nocover.jpg';
	}
	static function info_url($aid,$is_langtail=false)
	{
		if($is_langtail)
		{
			global $fake_langtail_info;
			$fake_url=$fake_langtail_info;
		}
		else
		{
			global $fake_info_url;
			$fake_url=$fake_info_url;
		}
		if(strpos($fake_url,'{subaid}')!==false)
		{
			$subaid=intval($aid/1000);
			$ret=str_replace(['{aid}','{subaid}'],[$aid,$subaid],$fake_url);
		}
		else
		{
			$ret=str_replace(['{aid}','{acode}'],$aid,$fake_url);
		}
		return $ret;
	}
	static function index_url($aid,$pid=1,$is_langtail=false)
	{
		if($is_langtail)
		{
			global $fake_langtail_indexlist;
			$fake_url=$fake_langtail_indexlist;
		}
		else
		{
			global $fake_indexlist;
			$fake_url=$fake_indexlist;
		}
		if(strpos($fake_url,'{subaid}')!==false)
		{
			$subaid=floor($aid/1000);
			if($pid==1)
			{
				$from=['/{subaid}/i','/{aid}|{acode}/i','/(-|_|\/)?{pid}/i'];
				$to=[$subaid,$aid,''];
			}
			else
			{
				$from=['/{subaid}/i','/{aid}|{acode}/i','/{pid}/i'];
				$to=[$subaid,$aid,$pid];
			}
		}
		else
		{
			if($pid==1)
			{
				$from=['/{aid}|{acode}/i','/(-|_|\/)?{pid}/i'];
				$to=[$aid,''];
			}
			else
			{
				$from=['/{aid}|{acode}/i','/{pid}/i'];
				$to=[$aid,$pid];
			}
		}
		$ret=preg_replace($from,$to,$fake_url);
		return $ret;
	}
	static function chapter_url($aid,$cid,$pid=1)
	{
		global $fake_chapter_url;
		if(strpos($fake_chapter_url,'{subaid}')!==false)
		{
			$subaid=intval($aid/1000);
			if($pid==1)
			{
				$ret=str_replace(['{subaid}','{aid}','{cid}'],[$subaid,$aid,$cid],$fake_chapter_url);
			}
			else
			{
				$ret=str_replace(['{subaid}','{aid}','{cid}'],[$subaid,$aid,$cid.'_'.$pid],$fake_chapter_url);
			}
		}
		else
		{
			if($pid==1)
			{
				$ret=str_replace(['{aid}','{acode}','{cid}'],[$aid,$aid,$cid],$fake_chapter_url);
			}
			else
			{
				$ret=str_replace(['{aid}','{acode}','{cid}'],[$aid,$aid,$cid.'_'.$pid],$fake_chapter_url);
			}
		}
		return $ret;
	}
	static function author_url($author)
	{
		global $is_ft;
		if($is_ft)$author=Convert::jt2ft($author);
		$ret='/author/'.urlencode($author).'/';
		return $ret;
	}
	static function tag_url($tag,$pid=1)
	{
		global $fake_tag;
		if($pid==1)
		{
			$from=['#{tag}#i','#{pid}.*$#i'];
			$to=[$tag,''];
		}
		else
		{
			$from=['#{tag}#i','#{pid}#i'];
			$to=[$tag,$pid];
		}
		return preg_replace($from,$to,$fake_tag);
	}
	static function category_url($sortid_or_sortcode,$pid=1)
	{
		global $fake_sort_url;
		$from=['{pid}','{sortid}','{sortcode}'];
		$to=[$pid,$sortid_or_sortcode,$sortid_or_sortcode];
		$ret=str_replace($from,$to,$fake_sort_url);
		return $ret;
	}
	static function fake2real($fake_url)
	{
		$from=['{subaid}','{acode}','{aid}','{cid}'];
		$to=['\d+','([a-z0-9_-]+)','(\d+)','(\d+)(_\d+)?'];
		$ret=str_replace($from,$to,$fake_url);
		$ret=str_replace(['/','.'],['\\/','\\.'],$ret);
		return '/^'.$ret.'?$/i';
	}
	static function sort2real($fake_sort_url)
	{
		$from=['{sortcode}','{sortid}','{pid}','.'];
		$to=['[a-z_-]+','[1-9]+','[1-9]+','\.'];
		$ret=str_replace($from,$to,$fake_sort_url);
		return '#^'.$ret.'?$#i';
	}
	static function indexlist2real($fake_indexlist)
	{
		$from=['/{subaid}/i','/{aid}/i','/{acode}/i','/(_|-|\/)?{pid}/i'];
		$to=['\d+','(\d+)','([a-z0-9_-]+)','(?:_|-|/)?(\d+)?'];
		$ret=preg_replace($from,$to,$fake_indexlist);
		$ret=str_replace(['/','.'],['\\/','\\.'],$ret);
		return '/^'.$ret.'?$/i';
	}
	static function tag2real($fake_tag)
	{
		$from=['{tag}','{pid}'];
		$to=['([^/]+)','(\d+)'];
		$ret=str_replace($from,$to,$fake_tag);
		return '#^'.$ret.'$#iU';
	}
	static function ss_errpage()
	{
		$errpage=dirname($_SERVER['DOCUMENT_ROOT']).'/shipsay/include/error.php';
		require_once $errpage;
		return;
	}
}
?>