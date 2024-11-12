<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Thread;
use App\Models\Reply;
use App\Policies\ThreadPolicy;
use App\Policies\ReplyPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Thread::class => ThreadPolicy::class,
        Reply::class => ReplyPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}