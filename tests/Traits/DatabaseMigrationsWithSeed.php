<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\DatabaseMigrations;

trait DatabaseMigrationsWithSeed
{
    use DatabaseMigrations;

    protected function shouldDropViews()
    {
        return true;
    }

    protected function shouldSeed()
    {
        return true;
    }
}
