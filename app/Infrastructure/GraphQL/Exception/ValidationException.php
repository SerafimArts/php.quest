<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLExceptionInterface;

final class ValidationException extends \Exception implements GraphQLExceptionInterface
{
    /**
     * @param ConstraintViolationInterface $violation
     * @param \Throwable|null $previous
     */
    public function __construct(private readonly ConstraintViolationInterface $violation, \Throwable $previous = null)
    {
        parent::__construct($violation->getMessage(), Response::HTTP_BAD_REQUEST, $previous);
    }

    /**
     * {@inheritDoc}
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getCategory(): string
    {
        return 'GRAPHQL_VALIDATION_FAILED';
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions(): array
    {
        $extensions = [];

        if ($code = $this->violation->getCode()) {
            $extensions['code'] = $code;
        }

        if ($path = $this->violation->getPropertyPath()) {
            $extensions['field'] = $path;
        }

        return $extensions;
    }
}
