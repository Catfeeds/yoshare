<?php

namespace App\Jobs;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $this->site->publish($this->site->default_theme);
        $this->site->publish($this->site->mobile_theme, 'iPhone');
    }
}
