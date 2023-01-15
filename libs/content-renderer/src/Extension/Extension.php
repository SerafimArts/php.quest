<?php

declare(strict_types=1);

namespace Local\ContentRenderer\Extension;

use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;

abstract readonly class Extension implements ExtensionInterface
{
    /**
     * @param Node $node
     *
     * @return array<non-empty-string>
     */
    protected function getClasses(Node $node): array
    {
        return $this->getAttributes($node, 'class');
    }

    /**
     * @param Node $node
     * @param array<non-empty-string> $classes
     *
     * @return void
     */
    protected function setClasses(Node $node, array $classes): void
    {
        $this->setAttributes($node, 'class', $classes);
    }

    /**
     * @param Node $node
     * @param non-empty-string $class
     *
     * @return void
     */
    protected function addClass(Node $node, string $class): void
    {
        $this->addAttribute($node, 'class', $class);
    }

    /**
     * @param Node $node
     * @param non-empty-string $attribute
     *
     * @return array<non-empty-string>
     */
    protected function getAttributes(Node $node, string $attribute): array
    {
        $classes = [];

        if ($node->data->has('attributes/' . $attribute)) {
            $classes = \explode(' ', $node->data->get('attributes/' . $attribute));
            $classes = \array_map(\trim(...), $classes);
            $classes = \array_filter($classes);
        }

        return $classes;
    }

    /**
     * @param Node $node
     * @param non-empty-string $attribute
     * @param array<non-empty-string> $values
     *
     * @return void
     */
    protected function setAttributes(Node $node, string $attribute, array $values): void
    {
        $node->data->set('attributes/' . $attribute, \implode(' ', $values));
    }

    /**
     * @param Node $node
     * @param non-empty-string $attribute
     * @param non-empty-string $value
     *
     * @return void
     */
    protected function addAttribute(Node $node, string $attribute, string $value): void
    {
        $values = $this->getAttributes($node, $attribute);

        if (!\in_array($value, $values, true)) {
            $values[] = $value;
        }

        $this->setAttributes($node, $attribute, $values);
    }
}
