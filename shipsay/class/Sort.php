<?php
class Sort
{
    // 获取分类名称
    static function ss_sortname($sortid, $is_fullname = false)
    {
        global $sortarr, $is_ft;
        // 检查 $sortid 是否存在于 $sortarr 中
        if (!isset($sortarr[(int)$sortid])) {
            return '';
        }
        $full_sortname = Text::ss_toutf8($sortarr[(int)$sortid]['caption']);
        if ($is_ft) {
            $full_sortname = Convert::jt2ft($full_sortname);
        }
        return $is_fullname ? $full_sortname : mb_substr($full_sortname, 0, 2);
    }

    // 获取分类 URL
    static function ss_sorturl($sortid)
    {
        global $sortarr, $is_sortid;
        if ($is_sortid) {
            return Url::category_url((int)$sortid);
        }
        // 检查 $sortid 是否存在于 $sortarr 中
        if (isset($sortarr[(int)$sortid])) {
            return Url::category_url($sortarr[(int)$sortid]['code']);
        }
        return '';
    }

    // 获取分类头部信息
    static function ss_sorthead()
    {
        global $sortarr, $is_sortid, $is_ft;
        $sorthead = [];
        foreach ($sortarr as $k => $v) {
            if ($is_sortid) {
                $sorthead[$k]['sorturl'] = Url::category_url((int)$k);
            } else {
                $sorthead[$k]['sorturl'] = Url::category_url($v['code']);
            }
            $v['caption'] = Text::ss_toutf8($v['caption']);
            if ($is_ft) {
                $v['caption'] = Convert::jt2ft($v['caption']);
            }
            $sorthead[$k]['sortname'] = $v['caption'];
            $sorthead[$k]['sortname_2'] = mb_substr($v['caption'], 0, 2);
        }
        return $sorthead;
    }
}
?>