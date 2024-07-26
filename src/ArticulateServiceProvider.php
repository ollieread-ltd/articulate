<?php
declare(strict_types=1);

namespace Articulate;

use Articulate\Managers\MetadataManager;
use Articulate\Managers\TypeManager;
use Articulate\Metadata\EntityMetadata;
use Illuminate\Support\ServiceProvider;

class ArticulateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge the config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/articulate.php',
            'articulate',
        );

        // Register the Articulate class
        $this->app->singleton(Articulate::class);

        // Register the type manager
        $this->app->singleton(
            TypeManager::class,
            fn (Articulate $articulate): TypeManager => $articulate->types()
        );

        // Register the metadata manager
        $this->app->singleton(
            MetadataManager::class,
            fn (Articulate $articulate): MetadataManager => $articulate->metadata()
        );

        // Initialise Articulate
        $this->app->make(Articulate::class)->init();
    }

    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/articulate.php' => config_path('articulate.php'),
            ]
        );

        // Set the connection resolver for the entity metadata
        EntityMetadata::setConnectionResolver($this->app['db']);

        // Map the entities
        $this->app->make(Articulate::class)->map();
    }
}
