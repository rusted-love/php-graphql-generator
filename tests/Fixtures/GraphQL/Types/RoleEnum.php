<?php
declare(strict_types=1);
namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

enum RoleEnum: string
{
    case Admin = 'ROLE_ADMIN';
    case Manager = 'Manager';
    case Developer = 'Developer';
}
