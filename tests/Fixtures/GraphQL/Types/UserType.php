<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Tests\Fixtures\GraphQL\Types;

final readonly class UserType
{
    public function id(): string
    {
        return 'user_1';
    }

    /**
     * @return RoleEnum[]
     */
    public function roles(): array
    {
        return [RoleEnum::Admin];
    }
}
