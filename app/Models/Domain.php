<?php

namespace App\Models;


class Domain
{
    const MARK_MEMBER = 'user';
    const MARK_GOODS = 'goods';
    public $site = '';
    public $theme = '';

    public function __construct($host = null, $theme = null)
    {
        if (empty($host)) {
            $host = request()->getHost();
        }

        $this->site = Site::findByDomain($host);

        if (empty($theme)) {
            if (is_mobile()) {
                $this->theme = $this->site->mobile_theme;
            } else {
                $this->theme = $this->site->default_theme;
            }
        }
        else {
            $this->theme = $theme;
        }
    }
}