<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL;

use App\Infrastructure\GraphQL\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLAggregateExceptionInterface;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLExceptionInterface;
use TheCodingMachine\GraphQLite\Types\InputTypeValidatorInterface;

final class InputTypeValidator implements InputTypeValidatorInterface
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * @param object $input
     *
     * @return void
     * @throws GraphQLAggregateExceptionInterface
     * @throws GraphQLExceptionInterface
     */
    public function validate(object $input): void
    {
        $errors = $this->validator->validate($input);

        $this->throwIfNotValid($errors);
    }

    /**
     * @param ConstraintViolationListInterface $errors
     *
     * @return void
     * @throws GraphQLAggregateExceptionInterface
     * @throws GraphQLExceptionInterface
     */
    private function throwIfNotValid(ConstraintViolationListInterface $errors): void
    {
        if ($errors->count() > 0) {
            throw new ValidationException($errors->get(0));
        }
    }
}
