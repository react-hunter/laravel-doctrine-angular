<?php

namespace App\Providers;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepository;
use App\Infrastructure\Repositories\DoctrineTaskRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TaskRepository::class, function (Application $app) {
            return new DoctrineTaskRepository(
                $app->make('em'),
                new ClassMetadata(Task::class)
            );
        });
    }
}
