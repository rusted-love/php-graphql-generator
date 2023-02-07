<?php
declare(strict_types=1);
namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

enum RoleEnum: string
{
    case Admin = 'ROLE_ADMIN';
    case Manager = 'Manager';
    case Developer = 'Developer';
    public static function fromName(string $name): self
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}
