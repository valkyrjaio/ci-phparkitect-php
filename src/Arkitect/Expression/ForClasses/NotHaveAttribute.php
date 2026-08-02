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

final class NotHaveAttribute implements Expression
{
    /** @var string */
    private $attribute;

    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function describe(ClassDescription $theClass, string $because): Description
    {
        return new Description("should not have the attribute {$this->attribute}", $because);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function evaluate(ClassDescription $theClass, Violations $violations, string $because): void
    {
        if (! $theClass->hasAttribute($this->attribute)) {
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
