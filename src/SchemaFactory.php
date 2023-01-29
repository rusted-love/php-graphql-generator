<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

use BladL\BestGraphQL\Exception\ResolverException;
use BladL\Time\TimeInterval;
use GraphQL\Error\SyntaxError;
use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\TypeDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Type\Schema;
use GraphQL\Utils\AST;
use GraphQL\Utils\BuildSchema;
use UnexpectedValueException;
use function assert;

final readonly class SchemaFactory
{
    public function __construct(private string $schemaPath, private string $cacheFilePath, private ?TimeInterval $cacheLifetime = null)
    {

    }

    private function isFileCacheTimeValid(string $name): bool
    {
        if (null === $this->cacheLifetime) {
            return true;
        }
        $mtime = filemtime($name);

        assert(false !== $mtime);
        return (time() - $mtime < $this->cacheLifetime->getSeconds());

    }

    private function isCacheFileValid(string $name): bool
    {
        if (file_exists($name)) {

            return $this->isFileCacheTimeValid($name);
        }
        return false;
    }

    /**
     * @throws SyntaxError
     * @throws ResolverException
     */
    public function parseSchema(): Schema
    {
        $cacheFilename = $this->cacheFilePath;

        if ($this->isCacheFileValid($cacheFilename)) {
            /** @noinspection UsingInclusionReturnValueInspection */
            $document = AST::fromArray(require $cacheFilename); // fromArray() is a lazy operation as well
        } else {
            $schemaPath = $this->schemaPath;
            if (!file_exists($schemaPath)) {
                throw new ResolverException('Specified schema path not exists');
            }
            $schemaContent = file_get_contents($schemaPath);
            assert(false !== $schemaContent);
            $document = Parser::parse($schemaContent);
            if (false === file_put_contents($cacheFilename, "<?php\nreturn " . var_export(AST::toArray($document), true) . ";\n")) {
                throw new UnexpectedValueException('Cache file not created');
            }
        }

        $typeConfigDecorator = static function (array $typeConfig, Node&TypeDefinitionNode $node, array $b): array {
            return $typeConfig;
        };
        assert($document instanceof DocumentNode);
        return BuildSchema::build($document, $typeConfigDecorator);
    }
}
