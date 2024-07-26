<?php
declare(strict_types=1);

namespace Articulate\Tests;

use Articulate\Articulate;
use Articulate\ArticulateServiceProvider;
use Articulate\Tests\Fixtures\UserMapping;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ArticulateServiceProvider::class,
        ];
    }

    #[Test]
    public function canCorrectlyRegisterMetadataMappings(): void
    {
        $articulate = $this->app->make(Articulate::class);

        dd($articulate->metadata()->map(UserMapping::class));
    }
}
