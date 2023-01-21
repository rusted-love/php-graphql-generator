<?php
declare(strict_types=1);

namespace Parser;

use JsonException;
use UnexpectedValueException;
use function array_key_exists;

final readonly class ClassFinder
{
    public function __construct(private string $appRoot)
    {
        if (!is_dir($this->appRoot)) {
            throw new UnexpectedValueException('App root incorrect');
        }
    }

    /**
     * @return class-string<object>[]
     */
    public function getClassesInNamespace(string $namespace): array
    {
        $files = scandir($this->getNamespaceDirectory($namespace));

        $classes = array_map(static function ($file) use ($namespace) {
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        return array_filter($classes, static function ($possibleClass) {
            return class_exists($possibleClass);
        });
    }

    /**
     * @return array<string,string>
     */
    private function getDefinedNamespaces(): array
    {
        $composerJsonPath = $this->appRoot . 'composer.json';
        try {
            $composerConfig = json_decode(file_get_contents($composerJsonPath), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new UnexpectedValueException('No correct composer json file found', previous: $e);
        }

        return (array)$composerConfig->autoload->{'psr-4'};
    }

    private function getNamespaceDirectory(string $namespace):?string
    {
        $composerNamespaces = $this->getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath($this->appRoot . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments))?:null;
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return null;
    }
}
