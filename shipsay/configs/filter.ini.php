<?php
// 使用布尔值代替 0，提高代码可读性
$ShipSayFilter['is_filter'] = false;

// 对过滤内容进行处理，去除不必要的特殊字符和换行
$ShipSayFilter['filter_ini'] = trim(str_replace(['$$$$', '♂'], '', '
天才一秒记住更新最快
笔趣阁船说
'));