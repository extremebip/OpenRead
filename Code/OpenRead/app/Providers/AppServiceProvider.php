<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * List of repositories that needs to be binded
     *
     * @var array
     */
    private $repositories = [
        'ChapterRepository',
        'GenreRepository',
        'RatingRepository',
        'StoryGenreRepository',
        'StoryRepository',
        'UserRepository',
    ];

    /**
     * List of services that needs to be binded
     *
     * @var array
     */
    private $services = [
        'AuthService',
        'UserProfileService',
        'WriterService',
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register all repositories
        foreach ($this->repositories as $repository) {
            $this->app->singleton("App\Repository\Contracts\I{$repository}",
                             "App\Repository\Repositories\\{$repository}");
        }

        // Register all services
        foreach ($this->services as $service) {
            $this->app->singleton("App\Service\Contracts\I{$service}", 
                             "App\Service\Modules\\{$service}");
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('toDropdown', function ($value_key, $text_key)
        {
            return $this->mapWithKeys(function ($item) use ($value_key, $text_key)
            {
                return [$item[$value_key] => $item[$text_key]];
            });
        });
    }
}
