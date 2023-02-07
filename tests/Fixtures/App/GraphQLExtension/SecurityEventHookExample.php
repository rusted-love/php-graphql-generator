<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\App\GraphQLExtension;

use BladL\BestGraphQL\Events\BeforeFieldResolvedListenerInterface;
use BladL\BestGraphQL\FieldResolver\FieldResolverArguments;
use BladL\BestGraphQL\Tests\Fixtures\App\Exception\AuthException;
use BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types\RoleEnum;
use GraphQL\Language\AST\ArgumentNode;
use GraphQL\Language\AST\DirectiveNode;
use GraphQL\Language\AST\EnumValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\ValueNode;
use UnexpectedValueException;
use function in_array;

final class SecurityEventHookExample implements BeforeFieldResolvedListenerInterface
{
    /**
     * @param RoleEnum[] $currentRoles
     */
    public function __construct(private readonly array $currentRoles = [])
    {
    }

    private const NAME = 'access';
    private const ROLE_ARG = 'roles';

    /**
     * @throws AuthException
     */
    private function assertHasOneOfRole(RoleEnum ...$roles): void
    {

        foreach ($roles as $role) {
            if (in_array($role, $this->currentRoles, true)) {
                return;
            }
        }
        throw new AuthException('Access denied. Role ' . implode(' or ', array_map(static fn(RoleEnum $role) => $role->name, $roles)) . ' required!');

    }

    /**
     * @throws AuthException
     */
    private function assertDirective(DirectiveNode $directiveNode):void {

        if ($directiveNode->name->value === self::NAME) {
            /**
             * @var RoleEnum[] $roles
             */
            $roles = [];
            foreach ($directiveNode->arguments as $argument) {
                /**
                 * @var ArgumentNode $argument
                 */
                if ($argument->name->value !== self::ROLE_ARG) {
                    throw new UnexpectedValueException('Directive ' . self::NAME . ' expected argument ' . self::ROLE_ARG);
                }
                $valueNode = $argument->value;
                if (!$valueNode instanceof ListValueNode) {
                    throw new UnexpectedValueException('Argument '.self::ROLE_ARG.' should be list');
                }
                foreach ($valueNode->values as $value) {
                    \assert($value instanceof ValueNode);

                    if (!$value instanceof EnumValueNode) {
                        throw new UnexpectedValueException('Argument ' . self::ROLE_ARG . ' expect enum');
                    }
                    $role = RoleEnum::fromName($value->value);
                    $roles[] = $role;
                }
            }
            $this->assertHasOneOfRole(...$roles);

        }
    }

    /**
     * @throws AuthException
     */
    public function beforeFieldResolve(FieldResolverArguments $info): void
    {

        $parentNode = $info->info->parentType->astNode;
        \assert(null !== $parentNode);
        foreach ($parentNode->directives as $directive) {
            $this->assertDirective($directive);
        }
        $node = $info->info->fieldDefinition->astNode;

        \assert(null !== $node);
        foreach ($node->directives as $directive) {
            /**
             * @var DirectiveNode $directive
             */
            $this->assertDirective($directive);
        }
    }
}
