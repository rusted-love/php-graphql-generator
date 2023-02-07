<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\FieldResolver\FieldResolvers;

use BladL\BestGraphQL\Exception\FieldResolverException;
use BladL\BestGraphQL\FieldResolver\FieldResolverAbstract;
use BladL\BestGraphQL\FieldResolver\FieldResolverArguments;
use ReflectionException;
use ReflectionMethod;

readonly class  ExternalTypeObjectFieldResolver extends FieldResolverAbstract
{

    /**
     * @throws FieldResolverException
     */
    protected function proceedResolve(FieldResolverArguments $info): mixed
    {
        $objectValue = $info->objectValue;
        \assert(\is_object($objectValue));
        $serviceClass = $this->project->getExternalObjectTypeClassService($objectValue);
        \assert(null !== $serviceClass);
        \assert(\class_exists($serviceClass));
        $service = $this->project->getConfig()->getService($serviceClass);
        $fieldName = $info->getFieldName();
        if (!method_exists($service, $fieldName)) {
            throw new FieldResolverException("Field $fieldName not defined for service type $serviceClass");
        }
        $callable = [$service, $fieldName];
        \assert(\is_callable($callable));
        $args = $info->args;

        return $this->project->getConfig()->callAutoWired($callable, $this->injectObjectValue(args: $args, service: $service, value: $objectValue, fieldName: $fieldName));
    }

    /**
     * @param array<string,mixed> $args
     * @return array<string,mixed>
     * @throws FieldResolverException
     */
    private function injectObjectValue(array $args, object $service, object $value, string $fieldName): array
    {
        try {
            $reflection = new ReflectionMethod($service, $fieldName);
        } catch (ReflectionException $e) {
            throw new FieldResolverException(message: "Method $fieldName reflection failed", previous: $e);
        }
        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            if ($type instanceof \ReflectionNamedType && $type->getName() === $value::class) {
                $args[$param->getName()] = $value;
                break;
            }

        }
        return $args;
    }

    public function supports(FieldResolverArguments $info): bool
    {
        return \is_object($info->objectValue) && $this->project->isExternalObject($info->objectValue);
    }
}
