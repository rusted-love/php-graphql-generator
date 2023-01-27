<?php
declare(strict_types=1);

namespace BladL\BestGraphQL;

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

final class SchemaGenerator
{
    public function __construct(public string $schema, public string $cacheFilePath)
    {
    }

    /**
     * @throws SyntaxError
     */
    public function parseSchema(): Schema
    {
        $cacheFilename = $this->cacheFilePath;
        if (file_exists($cacheFilename)) {
            /** @noinspection UsingInclusionReturnValueInspection */
            $document = AST::fromArray(require $cacheFilename); // fromArray() is a lazy operation as well
        } else {
            $document = Parser::parse($this->schema);
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
