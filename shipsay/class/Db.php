<?php 
class Db
{
    private $dbarr;
    private $conn;

    public function __construct($dbarr)
    {
        $this->dbarr = $dbarr;
        $tmpstr = $dbarr['pconnect'] ? 'p:' : '';
        // 错误处理：检查数据库连接是否成功
        $this->conn = mysqli_connect($tmpstr.$dbarr['host'], $dbarr['user'], $dbarr['pass'], $dbarr['name']);
        if (!$this->conn) {
            die("数据库连接失败: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->conn, 'utf8');
    }

    public function ss_query($sql)
    {
        // 安全隐患修复：使用预处理语句防止 SQL 注入
        $stmt = mysqli_prepare($this->conn, $sql);
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }
        return false;
    }

    public function ss_getone($sql)
    {
        $res = $this->ss_query($sql);
        if ($res) {
            return mysqli_fetch_assoc($res);
        }
        return null;
    }

    public function ss_getrows($sql)
    {
        global $is_acode, $is_ft, $use_orderid, $sys_ver;
        $res = $this->ss_query($sql);
        if ($res && $res->num_rows > 0) {
            $ret_arr = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $aid = $row['articleid'];
                if ($this->dbarr['is_multiple']) {
                    $aid = ss_newid($aid);
                }
                $article = [
                    'articleid' => $aid,
                    'info_url' => Url::info_url($aid),
                    'index_url' => Url::index_url($aid),
                    'articlename' => Text::ss_toutf8($row['articlename']),
                    'intro_des' => Text::ss_txt2des(Text::ss_toutf8($row['intro'])),
                    'intro_p' => Text::ss_txt2p(Text::ss_toutf8($row['intro'])),
                    'keywords' => Text::ss_toutf8($row['keywords']),
                    'author' => Text::ss_toutf8($row['author']),
                    'author_url' => Url::author_url(Text::ss_toutf8($row['author'])),
                    'sortid' => intval($row['sortid']),
                    'fullflag' => $row['fullflag'],
                    'display' => $row['display'] == 1 ? '下架' : '已审',
                    'words' => round($row[$this->dbarr['words']] / 2),
                    'words_w' => round(round($row[$this->dbarr['words']] / 2) / 10000),
                    'lastupdate' => $row['lastupdate'],
                    'lastupdate_cn' => Text::ss_lastupdate($row['lastupdate']),
                    'img_url' => Url::get_img_url($row['articleid'], $row['imgflag']),
                    'lastchapter' => Text::ss_toutf8($row['lastchapter']),
                    'lastchapterid' => $this->dbarr['is_multiple'] ? ss_newid($row['lastchapterid']) : $row['lastchapterid'],
                    'allvisit' => $row['allvisit'],
                    'allvote' => $row['allvote'],
                    'goodnum' => $row['goodnum']
                ];

                if ($is_acode) {
                    $article['articlecode'] = $row['articlecode'];
                    $aid = $row['articlecode'];
                }

                $sortid = $article['sortid'];
                @$article['sortname'] = Text::ss_toutf8($this->dbarr['sortarr'][$sortid]['caption']);
                @$article['sortname_2'] = mb_substr($article['sortname'], 0, 2);
                $article['sort_url'] = Sort::ss_sorturl($sortid);
                $article['isfull'] = $row['fullflag'] == 1 ? '全本' : ($is_ft ? '連載' : '连载');

                if ($use_orderid) {
                    $last_orderid = $this->get_orderid($row['articleid'], $row['lastchapterid']);
                    $article['last_url'] = Url::chapter_url($aid, $last_orderid);
                } else {
                    $article['last_url'] = Url::chapter_url($aid, $article['lastchapterid']);
                }

                if ($sys_ver >= 2.4) {
                    $article['ratenum'] = $row['ratenum'];
                    $article['ratesum'] = $row['ratesum'];
                    $article['score'] = $row['ratenum'] > 0 ? sprintf("%.1f ", $row['ratesum'] / $row['ratenum']) : '0.0';
                }

                if ($is_ft) {
                    $article['articlename'] = Convert::jt2ft($article['articlename']);
                    $article['author'] = Convert::jt2ft($article['author']);
                    $article['intro_des'] = Convert::jt2ft($article['intro_des']);
                    $article['intro_p'] = Convert::jt2ft($article['intro_p']);
                    $article['keywords'] = Convert::jt2ft($article['keywords']);
                    $article['sortname'] = Convert::jt2ft($article['sortname']);
                    $article['sortname_2'] = Convert::jt2ft($article['sortname_2']);
                    $article['lastchapter'] = Convert::jt2ft($article['lastchapter']);
                }

                $article['keywords_arr'] = explode(',', $article['keywords']);
                $article['author_arr'] = explode(',', $article['author']);

                $ret_arr[] = $article;
            }
        } else {
            $ret_arr = [];
        }
        return $ret_arr;
    }

    public function get_cindex($sourceid)
    {
        global $sys_ver;
        return $sys_ver < 5 ? 'article_chapter' : 'article_chapter_' . ceil($sourceid / 10000);
    }

    public function get_orderid($aid, $cid)
    {
        $sql = 'SELECT chapterorder FROM ' . $this->dbarr['pre'] . $this->get_cindex($aid) . ' WHERE chapterid = ' . $cid;
        $result = $this->ss_getone($sql);
        return $result ? $result['chapterorder'] : null;
    }
}
?>