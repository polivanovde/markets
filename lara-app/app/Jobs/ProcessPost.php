<?php

namespace App\Jobs;

use App\Models\Post;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;

class ProcessPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected Post $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Post $post
        )
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @param Post $post
     * @param Request $request
     * @return void
     */
    public function handle()
    {
        $this->post->save();
    }
}
