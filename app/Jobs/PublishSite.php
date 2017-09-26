<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Module;
use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishSite implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $site;

    /**
     * Create a new job instance.
     *
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->publish($this->site->default_theme);
        if ($this->site->default_theme != $this->site->mobile_theme) {
            $this->publish($this->site->mobile_theme, 'iPhone');
        }
    }

    public function publish($theme, $device = '')
    {
        $site = $this->site;
        $modules = Module::where('state', Module::STATE_ENABLE)->get();

        //创建站点目录
        $path = public_path("$site->directory/$theme->name");
        if (!is_dir($path)) {
            //创建模块目录
            @mkdir($path, 0755, true);
        }

        //生成首页
        $html = curl_get("http://$site->domain/index.html", [CURLOPT_USERAGENT => $device]);
        $file_html = "$path/index.html";
        file_put_contents($file_html, $html);

        foreach ($modules as $module) {
            $rows = call_user_func([$module->model_class, 'all']);
            $categories = Category::where('module_id', $module->id)->get();

            $path = public_path("$site->directory/$theme->name/$module->path");
            if (!is_dir($path)) {
                //创建模块目录
                @mkdir($path, 0755, true);
            }

            //生成列表页
            $html = curl_get("http://$site->domain/$module->path/index.html", [CURLOPT_USERAGENT => $device]);
            $file_html = "$path/index.html";
            file_put_contents($file_html, $html);

            //生成栏目页
            if ($module->fields()->where('name', 'category_id')->count()) {
                foreach ($categories as $category) {
                    $html = curl_get("http://$site->domain/$module->path/category-$category->id.html", [CURLOPT_USERAGENT => $device]);
                    $file_html = "$path/category-$category->id.html";
                    file_put_contents($file_html, $html);
                }
            }

            //生成详情页
            foreach ($rows as $row) {
                $html = curl_get("http://$site->domain/$module->path/detail-$row->id.html", [CURLOPT_USERAGENT => $device]);
                $file_html = "$path/detail-$row->id.html";
                file_put_contents($file_html, $html);
            }
        }
    }
}
