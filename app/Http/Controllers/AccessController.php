<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\AccessTotal;
use DB;

/**
 *  This Controller provides such access api
 *
 * @author: sel <17149103@qq.com>
 * @version: 1.0.1
 */
class AccessController extends Controller
{

    public $yesterday;    // 昨天
    public $today;        // 今天
    public $tomorrow;    // 明天
    public $day3;        // 前天
    public $day7;        // 最近7天
    public $day14;        // 最近14天
    public $day30;        // 最近30天
    public $day60;        // 最近60天

    public function __construct()
    {
        $today = strtotime(date('Y-m-d', time()));
        $this->today = date('Y-m-d H:i:s', $today);
        $this->yesterday = date('Y-m-d H:i:s', $today - 60 * 60 * 24);
        $this->tomorrow = date('Y-m-d H:i:s', $today + 60 * 60 * 24);
        $this->day3 = date('Y-m-d H:i:s', $today - 60 * 60 * 24 * 2);
        $this->day7 = date('Y-m-d H:i:s', $today - 60 * 60 * 24 * 7);
        $this->day14 = date('Y-m-d H:i:s', $today - 60 * 60 * 24 * 14);
        $this->day30 = date('Y-m-d H:i:s', $today - 60 * 60 * 24 * 30);
        $this->day60 = date('Y-m-d H:i:s', $today - 60 * 60 * 24 * 60);
    }

    /**
     *    后台访问首页统计页
     */
    public function admin()
    {
        // 今天和明天时间戳与Unix格式
        $today = strtotime(date('Y-m-d', time()));
        $tomorrow = $today + 60 * 60 * 24;
        $todayU = date('Y-m-d H:i:s', $today);
        $tomorrowU = date('Y-m-d H:i:s', $tomorrow);

        // 第一层总计
        $al = AccessLog::orderBy('created_at', 'asc')->first();
        if ($al) {
            $day = $today - strtotime($al->created_at->format("Y-m-d"));
            $day = $day < 0 ? -$day : $day;
            $sum = [
                'view' => AccessLog::sum('clicks'),                         // 总访问量
                'staytime' => round(AccessTotal::sum('staytime') / 60),     // 总浏览时长
                'ip' => AccessTotal::distinct()->count('ip'),               // 总访问ip
                'day' => round($day / (60 * 60 * 24), 1)                    // 总统计天数
            ];
        } else {
            // 不存在的时候模板设置为0
            $sum = [
                'view' => 0,
                'staytime' => 0,
                'ip' => 0,
                'day' => 0
            ];
        }

        // Url访问排行
        $url = array();
        $badge = ['bg-red', 'bg-yellow', 'bg-light-blue', 'bg-green', 'bg-green', 'bg-green'];
        $clickToday = AccessLog::whereBetween('created_at', [$todayU, $tomorrowU])->sum('clicks');
        $urlRank = AccessLog::whereBetween('created_at', [$todayU, $tomorrowU]) -> orderBy('clicks', 'desc') -> take(12)->get();
        foreach ($urlRank as $k => $v) {
            $v->badge = $badge[min($k, count($badge) -1)];
            $v->percent = round($v->clicks / $clickToday * 100, 2);
            $url[$k] = $v;
        }
        return view('admin', compact('sum', 'url'));
    }

    /**
     *    get tend data
     */
    public function tend($num)
    {
        $arr = array();
        $sql = "select sum(t0) t0,sum(t6) t6,sum(t12) t12,sum(t18) t18 from cms_access_logs where created_at between ? and ?";
        if ($num == 1) {
            $data = DB::select($sql, [$this->today, $this->tomorrow]);
            foreach ($data[0] as $k => $v) {
                array_push($arr, $v);
            }
            return json_encode($arr);
        } else if ($num == 2) {
            $data = DB::select($sql, [$this->yesterday, $this->today]);
            foreach ($data[0] as $k => $v) {
                array_push($arr, $v);
            }
            return json_encode($arr);
        } else if ($num == 3) {
            $data = DB::select($sql, [$this->day7, $this->tomorrow]);
            foreach ($data[0] as $k => $v) {
                array_push($arr, $v);
            }
            return json_encode($arr);
        } else if ($num == 4) {
            $data = DB::select($sql, [$this->day30, $this->tomorrow]);
            foreach ($data[0] as $k => $v) {
                array_push($arr, $v);
            }
            return json_encode($arr);
        }
    }

    /**
     *    get progress data
     *
     * @param  int $num
     * @return string
     */
    public function progress($num)
    {
        /* 浏览量 */
        // 今天
        $todayClick = AccessLog::whereBetween('created_at', [$this->today, $this->tomorrow])->sum('clicks');
        // 今天 + 昨天
        $todayClick2 = AccessLog::whereBetween('created_at', [$this->yesterday, $this->tomorrow])->sum('clicks');
        $todayClick2 = empty($todayClick2) ? 1 : $todayClick2;
        // 昨天
        $yesClick = AccessLog::whereBetween('created_at', [$this->yesterday, $this->today])->sum('clicks');
        // 昨天 + 前天
        $yesClick2 = AccessLog::whereBetween('created_at', [$this->day3, $this->today])->sum('clicks');
        $yesClick2 = empty($yesClick2) ? 1 : $yesClick2;
        // 最近7天
        $sevenClick = AccessLog::whereBetween('created_at', [$this->day7, $this->tomorrow])->sum('clicks');
        // 最近14天
        $sevenClick2 = AccessLog::whereBetween('created_at', [$this->day14, $this->tomorrow])->sum('clicks');
        $sevenClick2 = empty($sevenClick2) ? 1 : $sevenClick2;
        // 最近30天
        $monthClick = AccessLog::whereBetween('created_at', [$this->day30, $this->tomorrow])->sum('clicks');
        // 最近60天
        $monthClick2 = AccessLog::whereBetween('created_at', [$this->day60, $this->tomorrow])->sum('clicks');
        $monthClick2 = empty($monthClick2) ? 1 : $monthClick2;
        /* ip数 和 时长 */
        $sql = "select count(distinct ip) as ip,sum(staytime) as staytime from cms_access_totals where updated_at between ? and ?";
        // 今天
        $data = DB::select($sql, [$this->today, $this->tomorrow])[0];
        $todayIp = $data -> ip;
        $todayTime = empty($data -> staytime) ? 0 : $data -> staytime;
        // 今天和昨天
        $data = DB::select($sql, [$this->yesterday, $this->tomorrow])[0];
        $todayIp2 = empty($data -> ip) ? 1 : $data -> ip;
        $todayTime2 = empty($data->staytime) ? 1 : $data->staytime;
        // 昨天
        $data = DB::select($sql, [$this->yesterday, $this->today])[0];
        $yesIp = $data -> ip;
        $yesTime = empty($data -> staytime) ? 0 : $data -> staytime;
        // 昨天和前天
        $data = DB::select($sql, [$this->day3, $this->today])[0];
        $yesIp2 = empty($data -> ip) ? 1 : $data -> ip;
        $yesTime2 = empty($data->staytime) ? 1 : $data->staytime;
        // 最近7天
        $data = DB::select($sql, [$this->day7, $this->tomorrow])[0];
        $sevenIp = $data->ip;
        $sevenTime = empty($data -> staytime) ? 0 : $data -> staytime;
        // 最近14天
        $data = DB::select($sql, [$this->day14, $this->tomorrow])[0];
        $sevenIp2 = empty($data -> ip) ? 1 : $data -> ip;
        $sevenTime2 = empty($data -> staytime) ? 1 : $data -> staytime;
        // 最近一个月
        $data = DB::select($sql, [$this->day30, $this->tomorrow])[0];
        $monthIp = $data->ip;
        $monthTime = empty($data->staytime) ? 0 : $data->staytime;
        // 最近2个月
        $data = DB::select($sql, [$this->day60, $this->tomorrow])[0];
        $monthIp2 = empty($data -> ip) ? 1 : $data -> ip;
        $monthTime2 = empty($data -> staytime) ? 1 : $data -> staytime;

        /* 判断参数时间 */
        if ($num == '1') {
            $pv = ceil($todayClick / $todayClick2 * 100);
            $ip = ceil($todayIp / $todayIp2 * 100);
            $staytime = ceil($todayTime / $todayTime2 * 100);
            $arr = [
                'pv' => $pv,
                'numPv' => $todayClick,
                'ip' => $ip,
                'numIp' => $todayIp,
                'staytime' => $staytime,
                'numTime' => $todayTime
            ];
            return json_encode($arr);
        } else if ($num == '2') {
            $pv = ceil($yesClick / $yesClick2 * 100);
            $ip = ceil($yesIp / $yesIp2 * 100);
            $staytime = ceil($yesTime / $yesTime2 * 100);
            $arr = [
                'pv' => $pv,
                'numPv' => $yesClick,
                'ip' => $ip,
                'numIp' => $yesIp,
                'staytime' => $staytime,
                'numTime' => $yesTime
            ];
            return json_encode($arr);
        } else if ($num == '3') {
            $pv = ceil($sevenClick / $sevenClick2 * 100);
            $ip = ceil($sevenIp / $sevenIp2 * 100);
            $staytime = ceil($sevenTime / $sevenTime2 * 100);
            $arr = [
                'pv' => $pv,
                'numPv' => $sevenClick,
                'ip' => $ip,
                'numIp' => $sevenIp,
                'staytime' => $staytime,
                'numTime' => $sevenTime
            ];
            return json_encode($arr);
        } else if ($num == '4') {
            $pv = ceil($monthClick / $monthClick2 * 100);
            $ip = ceil($monthIp / $monthIp2 * 100);
            $staytime = ceil($monthTime / $monthTime2 * 100);
            $arr = [
                'pv' => $pv,
                'numPv' => $monthClick,
                'ip' => $ip,
                'numIp' => $monthIp,
                'staytime' => $staytime,
                'numTime' => $monthTime
            ];
            return json_encode($arr);
        }
    }

    /**
     *    get china map data
     *
     * @param int $limit
     * @return string
     */
    public function area($limit = null)
    {
        if ($limit) {
            $limit = 'limit ' . $limit;
        }
        $sql = "SELECT area as name,count(area) as value FROM cms_access_totals group by name order by value desc " . $limit;
        $data = DB::select($sql);
        return json_encode($data);
    }

    /**
     *    get browser data
     *
     * @return string
     */
    public function browser()
    {
        $data = DB::select("SELECT browser as label,count(browser) as value FROM cms_access_totals group by browser");
        for ($i = 0; $i < count($data); $i++) {
            switch ($data[$i]->label) {
                case '谷歌浏览器':
                    $data[$i]->color = '#dd4b39';
                    $data[$i]->highlight = '#dd4b39';
                    break;
                case '火狐浏览器':
                    $data[$i]->color = '#00a65a';
                    $data[$i]->highlight = '#00a65a';
                    break;
                case '苹果浏览器':
                    $data[$i]->color = '#f39c12';
                    $data[$i]->highlight = '#f39c12';
                    break;
                case '欧朋浏览器':
                    $data[$i]->color = '#00c0ef';
                    $data[$i]->highlight = '#00c0ef';
                    break;
                case 'IE浏览器':
                    $data[$i]->color = '#3c8dbc';
                    $data[$i]->highlight = '#3c8dbc';
                    break;
                case '其他浏览器':
                    $data[$i]->color = '#d2d6de';
                    $data[$i]->highlight = '#d2d6de';
                    break;
            }
        }
        return json_encode($data);
    }
}
