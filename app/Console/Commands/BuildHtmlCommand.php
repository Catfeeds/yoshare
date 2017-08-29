<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Module;
use App\Models\Site;
use Illuminate\Console\Command;

class BuildHtmlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:html {site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the html file for the site.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $site = Site::find($this->argument('site'));

        $this->buildByTheme($site, $site->desktop_theme);
        $this->buildByTheme($site, $site->mobile_theme);

        $this->info($site->title . '静态页面生成完成!');
    }

    public function buildByTheme($site, $theme)
    {
        $modules = Module::where('state', Module::STATE_ENABLE)->get();
        foreach ($modules as $module) {
            $rows = call_user_func([$module->model_class, 'all']);
            $categories = Category::where('module_id', $module->id)->get();

            //生成列表页
            $indexView = resource_path("views/themes/$theme/$module->plural/index.blade.php");
            if (file_exists($indexView)) {
                //创建模块目录
                $path = public_path("sites/$site->name/$theme/$module->plural");
                if (!is_dir($path)) {
                    @mkdir($path, 0755, true);
                }

                $html = view("themes.$theme.$module->plural.index", ['site' => $site, 'categories' => $categories, $module->plural => $rows])->__toString();
                $file_html = "$path/index.html";
                file_put_contents($file_html, $html);
            }

            //生成栏目页
            $categoryView = resource_path("views/themes/$theme/$module->plural/category.blade.php");
            if (file_exists($categoryView)) {
                if ($module->fields()->where('name', 'category_id')->count()) {
                    foreach ($categories as $category) {
                        $items = $rows->where('category_id', $category->id);
                        $html = view("themes.$theme.$module->plural.category", ['site' => $site, 'category' => $category, $module->plural => $items])->__toString();
                        $file_html = "$path/category-$category->id.html";
                        file_put_contents($file_html, $html);
                    }
                }
            }

            //生成详情页
            $detailView = resource_path("views/themes/$theme/$module->plural/detail.blade.php");
            if (file_exists($detailView)) {
                foreach ($rows as $row) {
                    $html = view("themes.$theme.$module->plural.detail", ['site' => $site, $module->singular => $row])->__toString();
                    $file_html = "$path/detail-$row->id.html";
                    file_put_contents($file_html, $html);
                }
            }
        }
    }
}
