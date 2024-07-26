<?php
declare(strict_types=1);

namespace Articulate\Metadata;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class DiscoverMetadata
{
    /**
     * @param string $path
     * @param string $basePath
     * @param string $namespace
     *
     * @return array<string, class-string<\Articulate\Metadata\Mapping<object>>>
     */
    public static function within(string $path, string $namespace): array
    {
        if (! is_dir($path)) {
            return [];
        }

        $finder = Finder::create()->files()->in($path)->name('*.php');

        $metadata = [];

        foreach ($finder as $file) {
            try {
                $reflection = new ReflectionClass(self::classFromFile($file, $path, $namespace));
            } catch (ReflectionException $e) {
                continue;
            }

            if (! $reflection->isInstantiable() || ! $reflection->isSubclassOf(Mapping::class)) {
                continue;
            }

            $metadata[$file->getFilename()] = $reflection->getName();
        }

        return $metadata;
    }

    private static function classFromFile(SplFileInfo $file, string $basePath, string $namespace): string
    {
        return str_replace(
            [DIRECTORY_SEPARATOR, $basePath],
            ['\\', $namespace],
            Str::replaceLast('.php', '', $file->getFilename())
        );
    }
}
