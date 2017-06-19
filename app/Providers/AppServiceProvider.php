<?php

namespace App\Providers;

use App\Models\Content;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $updating = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Content::updated(function ($content) {
            //修改时同步镜像数据
            if (!$this->updating) {
                $this->updating = true;
                $content->sync();
                $this->updating = false;
            }
        });

        Content::deleting(function ($content) {
            //删除数据时修改状态
            $this->updating = true;
            $content->state = Content::STATE_DELETED;
            $content->save();
            $this->updating = false;
        });

        Content::created(function ($content) {
            //同步ID默认为ID
            $this->updating = true;
            if ($content->sync_id == 0) {
                $content->sync_id = $content->id;
                $content->save();
            }
            $this->updating = false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
