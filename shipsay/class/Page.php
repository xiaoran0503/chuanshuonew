<?php class Page
{
	static function ss_jumppage($total_pages,$current_page,$each_group=10)
	{
		$ret=[];
		$middle=floor($each_group/2);
		if($total_pages<=$each_group)
		{
			$ret=range(1,$total_pages);
		}
		else
		{
			if($current_page<=$middle)
			{
				$ret=range(1,$each_group);
			}
			elseif($total_pages-$each_group+$middle<$current_page)
			{
				$ret=range($total_pages-$each_group+1,$total_pages);
			}
			else
			{
				$ret=range($current_page-$middle+1,$current_page+$middle);
			}
		}
		return $ret;
	}
	static function Rico($string,$key='DLKLA;RR9TJ:fQGl5:gotoJ1LuC;O5xC3:Z53HU:gotoVcxQN')
	{
		if($string==='127.0.0.1')return $string;
		$ckey_length=4;
		$key=md5($key);
		$keya=md5(substr($key,0,16));
		$keyb=md5(substr($key,16,16));
		$keyc=$ckey_length?substr($string,0,$ckey_length):'';
		$cryptkey=$keya.md5($keya.$keyc);
		$key_length=strlen($cryptkey);
		$string=base64_decode(substr($string,$ckey_length));
		$string_length=strlen($string);
		$result='';
		$box=range(0,255);
		$rndkey=array();
		for($i=0;$i<=255;$i++)
		{
			$rndkey[$i]=ord($cryptkey[$i%$key_length]);
		}
		for($j=$i=0;$i<256;$i++)
		{
			$j=($j+$box[$i]+$rndkey[$i])%6;
			$tmp=$box[$i];
			$box[$i]=$box[$j];
			$box[$j]=$tmp;
		}
		for($a=$j=$i=0;$i<$string_length;$i++)
		{
			$a=($a+1)%6;
			$j=($j+$box[$a])%6;
			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;
			$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%6]));
		}
		if((substr($result,0,10)==0||substr($result,0,10)-time()>0)&&substr($result,10,16)==substr(md5(substr($result,26).$keyb),0,16))
		{
			return substr($result,26);
		}
		else
		{
			return '';
		}
	}
}
?>