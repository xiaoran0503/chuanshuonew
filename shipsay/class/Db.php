<?php class Db
{
	private $dbarr;
	private $conn;
	public function __construct($dbarr)
	{
		$this->dbarr=$dbarr;
		$tmpstr=$dbarr['pconnect']?'p:':'';
		$this->conn=mysqli_connect($dbarr['host'],$dbarr['user'],$dbarr['pass'],$dbarr['name']);
		mysqli_set_charset($this->conn,'utf8');
	}
	public function ss_query($sql)
	{
		return mysqli_query($this->conn,$sql);
	}
	public function ss_getone($sql)
	{
		$res=mysqli_query($this->conn,$sql);
		return mysqli_fetch_assoc($res);
	}
	public function ss_getrows($sql)
	{
		global $is_acode,$is_ft,$use_orderid,$sys_ver;
		$res=mysqli_query($this->conn,$sql);
		if($res->num_rows)
		{
			$k=0;
			while($row=mysqli_fetch_assoc($res))
			{
				$aid=$row['articleid'];
				if($this->dbarr['is_multiple'])$aid=ss_newid($aid);
				$ret_arr[$k]['articleid']=$aid;
				if($is_acode)$aid=$ret_arr[$k]['articlecode']=$row['articlecode'];
				$ret_arr[$k]['info_url']=Url::info_url($aid);
				$ret_arr[$k]['index_url']=Url::index_url($aid);
				$ret_arr[$k]['articlename']=Text::ss_toutf8($row['articlename']);
				$ret_arr[$k]['intro_des']=Text::ss_txt2des(Text::ss_toutf8($row['intro']));
				$ret_arr[$k]['intro_p']=Text::ss_txt2p(Text::ss_toutf8($row['intro']));
				$ret_arr[$k]['keywords']=Text::ss_toutf8($row['keywords']);
				$ret_arr[$k]['author']=Text::ss_toutf8($row['author']);
				$ret_arr[$k]['author_url']=Url::author_url($ret_arr[$k]['author']);
				$sortid=intval($row['sortid']);
				$ret_arr[$k]['sortid']=$sortid;
				@$ret_arr[$k]['sortname']=Text::ss_toutf8($this->dbarr['sortarr'][$sortid]['caption']);
				@$ret_arr[$k]['sortname_2']=mb_substr($ret_arr[$k]['sortname'],0,2);
				$ret_arr[$k]['sort_url']=Sort::ss_sorturl($sortid);
				$ret_arr[$k]['fullflag']=$row['fullflag'];
				$ret_arr[$k]['isfull']=$row['fullflag']==1?'全本':($is_ft?'連載':'连载');
				$ret_arr[$k]['display']=$row['display']==1?'下架':'已审';
				$ret_arr[$k]['words']=round($row[$this->dbarr['words']]/2);
				$ret_arr[$k]['words_w']=round($ret_arr[$k]['words']/10000);
				$ret_arr[$k]['lastupdate']=$row['lastupdate'];
				$ret_arr[$k]['lastupdate_cn']=Text::ss_lastupdate($row['lastupdate']);
				$ret_arr[$k]['img_url']=Url::get_img_url($row['articleid'],$row['imgflag']);
				$ret_arr[$k]['lastchapter']=Text::ss_toutf8($row['lastchapter']);
				$ret_arr[$k]['lastchapterid']=$this->dbarr['is_multiple']?ss_newid($row['lastchapterid']):$row['lastchapterid'];
				if($use_orderid)
				{
					$last_orderid=$this->get_orderid($row['articleid'],$row['lastchapterid']);
					$ret_arr[$k]['last_url']=Url::chapter_url($aid,$last_orderid);
				}
				else
				{
					$ret_arr[$k]['last_url']=Url::chapter_url($aid,$ret_arr[$k]['lastchapterid']);
				}
				$ret_arr[$k]['allvisit']=$row['allvisit'];
				$ret_arr[$k]['allvote']=$row['allvote'];
				$ret_arr[$k]['goodnum']=$row['goodnum'];
				if($sys_ver>=2.4)
				{
					$ret_arr[$k]['ratenum']=$row['ratenum'];
					$ret_arr[$k]['ratesum']=$row['ratesum'];
					$ret_arr[$k]['score']=$row['ratenum']>0?sprintf("%.1f ",$row['ratesum']/$row['ratenum']):'0.0';
				}
				if($is_ft)
				{
					$ret_arr[$k]['articlename']=Convert::jt2ft($ret_arr[$k]['articlename']);
					$ret_arr[$k]['author']=Convert::jt2ft($ret_arr[$k]['author']);
					$ret_arr[$k]['intro_des']=Convert::jt2ft($ret_arr[$k]['intro_des']);
					$ret_arr[$k]['intro_p']=Convert::jt2ft($ret_arr[$k]['intro_p']);
					$ret_arr[$k]['keywords']=Convert::jt2ft($ret_arr[$k]['keywords']);
					$ret_arr[$k]['sortname']=Convert::jt2ft($ret_arr[$k]['sortname']);
					$ret_arr[$k]['sortname_2']=Convert::jt2ft($ret_arr[$k]['sortname_2']);
					$ret_arr[$k]['lastchapter']=Convert::jt2ft($ret_arr[$k]['lastchapter']);
				}
				$ret_arr[$k]['keywords_arr']=explode(',',$ret_arr[$k]['keywords']);
				$ret_arr[$k]['author_arr']=explode(',',$ret_arr[$k]['author']);
				$k++;
			}
		}
		else
		{
			$ret_arr='';
		}
		return $ret_arr;
	}
	public function get_cindex($sourceid)
	{
		global $sys_ver;
		if($sys_ver<5)
		{
			return 'article_chapter';
		}
		else
		{
			return 'article_chapter_'.ceil($sourceid/10000);
		}
	}
	public function get_orderid($aid,$cid)
	{
		$sql='SELECT chapterorder FROM '.$this->dbarr['pre'].$this->get_cindex($aid).' WHERE chapterid = '.$cid;
		return $this->ss_getone($sql)['chapterorder'];
	}
}
?>