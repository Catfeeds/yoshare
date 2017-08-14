<?php

/**
 * 产生随机字符串
 *
 * @param    int $length 输出长度
 * @param    string $chars 可选的 ，默认为 0123456789
 * @return   string     字符串
 */
function random($length, $chars = '0123456789')
{
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 获取完整URL
 *
 * @param $url
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function get_url($url)
{
    if (empty($url)) {
        return '';
    } else {
        return url($url);
    }
}

/**
 * 获取图片URL
 *
 * @param $url
 * @return string
 */
function get_image_url($url)
{
    if (empty($url)) {
        return '';
    } elseif (substr_compare(strtolower($url), 'http', 0, 4) == 0) {
        return $url;
    } else {
        return config('site.cdn.image_url') . $url;
    }
}

/**
 * 获取视频URL
 *
 * @param $url
 * @return string
 */
function get_video_url($url)
{
    if (empty($url)) {
        return '';
    } elseif (substr_compare(strtolower($url), 'http', 0, 4) == 0) {
        return $url;
    } else {
        return config('site.cdn.video_url') . $url;
    }
}

/**
 * 获取静态文件URL
 *
 * @param $url
 * @return string
 */
function get_static_url($url)
{
    if (empty($url)) {
        return '';
    } elseif (substr_compare(strtolower($url), 'http', 0, 4) == 0) {
        return $url;
    } else {
        return config('site.cdn.static_url') . $url;
    }
}

/**
 * 替换内容中的图片URL
 *
 * @param $content
 * @return mixed
 */
function replace_content_url($content)
{
    return str_replace('"/uploads/images', '"' . config('site.cdn.image_url') . '/uploads/images', $content);
}

/**
 * HTTP GET
 *
 * @param $url
 * @return string
 */
function curl_get($url)
{
    //初始化
    $ch = curl_init();

    //设置选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    //执行并获取内容
    $result = curl_exec($ch);

    //释放curl句柄
    curl_close($ch);

    return $result;
}

/**
 * HTTP POST
 *
 * @param $url
 * @param $data
 * @return string
 */
function curl_post($url, $data)
{
    //初始化
    $ch = curl_init();

    //设置选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    //执行并获取内容
    $result = curl_exec($ch);

    //释放curl句柄
    curl_close($ch);

    return $result;
}

/**
 * 随机字符串
 *
 * @param int $len
 * @return string
 */
function str_rand($len = 6)
{
    $chars = 'abdefghijklmnopqrstuvwxyz0123456789';
    mt_srand((double)microtime() * 1000000 * getmypid());
    $password = '';
    while (strlen($password) < $len)
        $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
    return $password;
}

/**
 * 从缓存或回调函数中获取值（正式环境）
 * 从回调函数中获取值（开发环境）
 *
 * @param $key
 * @param $minutes
 * @param $callback
 * @return mixed
 */
function cache_remember($key, $minutes, $callback)
{
    if (env('APP_DEBUG')) {
        return call_user_func($callback);
    } else {
        return Cache::remember($key, $minutes, $callback);
    }
}

/**
 * 短时间显示, 几秒前, 几分钟前...
 *
 * @param $time
 * @return string
 */
function time_trans($time)
{
    $t = time() - $time;
    $f = array(
        '31536000' => '年',
        '2592000' => '个月',
        '604800' => '星期',
        '86400' => '天',
        '3600' => '小时',
        '60' => '分钟',
        '1' => '秒'
    );
    foreach ($f as $k => $v) {
        if (0 != $c = floor($t / (int)$k)) {
            return $c . $v . '前';
        }
    }
}

/**
 * 获取客户端真实IP
 *
 * @return string
 */
function get_client_ip()
{
    return isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : Request::getClientIp();
}

/**
 * 数组转对象
 *
 * @param $array
 * @return StdClass
 */
function array_to_object($array)
{
    if (is_array($array)) {
        $obj = new StdClass();
        foreach ($array as $key => $val) {
            $obj->$key = $val;
        }
    } else {
        $obj = $array;
    }
    return $obj;
}

/**
 * 对象转数组
 *
 * @param $object
 * @return mixed
 */
function object_to_array($object)
{
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    } else {
        $array = $object;
    }
    return $array;
}

/**
 * 字符串数组转选项
 *
 * @param $array
 * @return array
 */
function array_to_option($array)
{
    if (is_array($array)) {
        foreach ($array as $key => $val) {
            $array[$val] = $val;
            unset($array[$key]);
        }
    }

    return $array;
}

/**
 * 字符串(逗号分隔)转选项
 *
 * @param $string
 * @return array
 */
function string_to_option($string)
{
    $array = explode(',', $string);

    return array_to_option($array);
}