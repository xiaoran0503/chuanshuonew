<?php class Sort
{
	static function ss_sortname($sortid,$is_fullname=false)
	{
		global $sortarr;
		global $is_ft;
		$full_sortname=Text::ss_toutf8($sortarr[intval($sortid)]['caption']);
		if($is_ft)$full_sortname=Convert::jt2ft($full_sortname);
		return $is_fullname?$full_sortname:@mb_substr($full_sortname,0,2);
	}
	static function ss_sorturl($sortid)
	{
		global $sortarr;
		global $is_sortid;
		if($is_sortid)
		{
			return Url::category_url($sortid);
		}
		else
		{
			foreach($sortarr as $k=>$v)
			{
				if($k==intval($sortid))
				{
					return Url::category_url($v['code']);
					break;
				}
			}
		}
	}
	static function ss_sorthead()
	{
		global $sortarr;
		global $is_sortid;
		global $is_ft;
		foreach($sortarr as $k=>$v)
		{
			if($is_sortid)
			{
				$sorthead[$k]['sorturl']=Url::category_url($k);
			}
			else
			{
				$sorthead[$k]['sorturl']=Url::category_url($v['code']);
			}
			$v['caption']=Text::ss_toutf8($v['caption']);
			if($is_ft)$v['caption']=Convert::jt2ft($v['caption']);
			$sorthead[$k]['sortname']=$v['caption'];
			$sorthead[$k]['sortname_2']=@mb_substr($v['caption'],0,2);
		}
		return $sorthead;
	}
}
?>