<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja PHPArkitect package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Arkitect\Expression\ForClasses;

use Arkitect\Analyzer\ClassDescription;
use Arkitect\Expression\Description;
use Arkitect\Expression\Expression;
use Arkitect\Rules\Violation;
use Arkitect\Rules\ViolationMessage;
use Arkitect\Rules\Violations;
use Override;

use function array_pop;
use function count;
use function explode;
use function str_contains;

final class HaveNameContainingComponent implements Expression
{
    /**
     * Get the component name that a class must carry.
     *
     * A namespace takes the form `Root\Component\...\Segment`, so the component
     * is the second segment. A namespace of two segments has no component
     * segment, so the root names the component: `Sindri\Provider` gives
     * `Sindri`. A class without a namespace has no component.
     */
    private static function getComponent(string $fqcn): string|null
    {
        $parts = explode('\\', $fqcn);

        array_pop($parts);

        if ($parts === []) {
            return null;
        }

        return count($parts) >= 3
            ? $parts[1]
            : $parts[0];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function describe(ClassDescription $theClass, string $because): Description
    {
        $component = self::getComponent($theClass->getFQCN());

        if ($component === null) {
            return new Description('should have a name that contains its component name', $because);
        }

        return new Description("should have a name that contains the component name {$component}", $because);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function evaluate(ClassDescription $theClass, Violations $violations, string $because): void
    {
        $component = self::getComponent($theClass->getFQCN());

        if ($component === null) {
            return;
        }

        if (str_contains($theClass->getName(), $component)) {
            return;
        }

        $violations->add(
            Violation::create(
                $theClass->getFQCN(),
                ViolationMessage::selfExplanatory($this->describe($theClass, $because)),
                $theClass->getFilePath()
            )
        );
    }
}
