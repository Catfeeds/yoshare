<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishPage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
    protected $module;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @param $site
     * @param $module
     * @param $id
     */
    public function __construct($site, $module, $id)
    {
        $this->site = $site;
        $this->module = $module;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $site = $this->site;
        $theme = $site->default_theme;
        $device = '';
        $module = $this->module;
        $id = $this->id;

        //创建站点目录
        $path = public_path("$site->directory/$theme->name");
        if (!is_dir($path)) {
            //创建模块目录
            @mkdir($path, 0755, true);
        }

        $path = public_path("$site->directory/$theme->name/$module->path");
        if (!is_dir($path)) {
            //创建模块目录
            @mkdir($path, 0755, true);
        }

        $html = curl_get("http://$site->domain/$module->path/detail-$id.html", [CURLOPT_USERAGENT => $device]);
        $file_html = "$path/detail-$id.html";
        file_put_contents($file_html, $html);
    }
}
