<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Exception;

use Throwable;

class ClientSafeException extends \Exception implements GraphQLExceptionInterface
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array<string, mixed> $extensions
     */
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, private readonly array $extensions = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
