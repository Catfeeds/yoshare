<?php

namespace App\Libraries;

use Exception;

class Sms
{
    protected $site_id = 1;

    public function __construct($site_id)
    {
        $this->site_id = $site_id;
    }

    //根据模板获取内容
    public function getContent($id, $code)
    {
        $template = config("site.$this->site_id.sms.template.$id");

        if (empty($template)) {
            throw new Exception('无此模板类型', -1);
        }

        return str_replace('@', $code, $template);
    }

    public function getOtherContent($id, $word)
    {
        $template = vsprintf(config("site.$this->site_id.sms.template.$id"), $word);

        if (empty($template)) {
            throw new Exception('无此模板类型', -1);
        }

        return $template;
    }

    //发送函数
    public function send($mobile, $content, $sendTime = '', $extno = '')
    {
        $ch = curl_init(config("site.$this->site_id.sms.url"));
        $args = array(
            'action' => config("site.$this->site_id.sms.action"),
            'account' => config("site.$this->site_id.sms.account"),
            'password' => config("site.$this->site_id.sms.password"),
            'mobile' => $mobile,
            'content' => $content,
            'sendTime' => $sendTime,
            'extno' => $extno,
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            return false;
        }
        $xml = simplexml_load_string($ret);
        if ($xml->returnstatus == 'Success') {
            return true;
        }
        return false;
    }
}