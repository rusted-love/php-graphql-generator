<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Exception;


use Exception;
use Throwable;

final class ResolverException extends Exception implements GraphQLExceptionInterface
{
    /**
     * @param array<string,mixed> $extensions
     */
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, private readonly array $extensions = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public function isClientSafe(): bool
    {
        return false;
    }

    /**
     * @return array<string,mixed>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
