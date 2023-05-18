<?php

namespace Core\Auth\Commands;

use Core\Auth\MenuRegistry;
use Illuminate\Console\Command;

class MenuCacheReset extends Command
{
    protected $signature = 'menu:flush';

    protected $description = 'Reset the menu cache';

    public function handle()
    {
        if (app(MenuRegistry::class)->forgetCachedMenus()) {
            $this->info('Menu cache flushed.');
        } else {
            $this->error('Unable to flush cache.');
        }
    }
}
