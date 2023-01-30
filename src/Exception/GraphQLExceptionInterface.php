<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Exception;

use GraphQL\Error\ClientAware;
use Throwable;

interface GraphQLExceptionInterface extends ClientAware, Throwable
{
    /**
     * Returns the "extensions" object attached to the GraphQL error.
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array;
}
