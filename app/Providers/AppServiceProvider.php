<?php

namespace App\Providers;

use App\Eloquent\Entries\CollectionRepository;
use App\Eloquent\Entries\EntryQueryBuilder;
use App\Eloquent\Entries\EntryRepository;
use App\Models\EntryModel;
use Illuminate\Support\ServiceProvider;
use Statamic\Contracts\Entries\CollectionRepository as CollectionRepositoryContract;
use Statamic\Contracts\Entries\EntryRepository as EntryRepositoryContract;
use Statamic\Statamic;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Statamic::repository(EntryRepositoryContract::class, EntryRepository::class);
        Statamic::repository(CollectionRepositoryContract::class, CollectionRepository::class);

        $this->app->bind(EntryQueryBuilder::class, function () {
            return new EntryQueryBuilder(EntryModel::query());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Statamic::script('app', 'cp');
        // Statamic::style('app', 'cp');
    }
}
