<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja PHPArkitect package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Arkitect\Tests\Unit\Expression\ForClasses;

use Arkitect\Analyzer\ClassDescriptionBuilder;
use Arkitect\Rules\Violations;
use Attribute;
use Valkyrja\Arkitect\Expression\ForClasses\NotHaveAttribute;
use Valkyrja\Arkitect\Tests\Abstract\ArkitectTestCase;

final class NotHaveAttributeTest extends ArkitectTestCase
{
    public function testDescribeMentionsAttributeAndReason(): void
    {
        $expression = new NotHaveAttribute(Attribute::class);

        $class = new ClassDescriptionBuilder()
            ->setClassName('App\\Foo')
            ->setFilePath('/app/Foo.php')
            ->build();

        self::assertSame(
            'should not have the attribute Attribute because reasons',
            $expression->describe($class, 'reasons')->toString(),
        );
    }

    public function testEvaluateAddsNoViolationWhenAttributeAbsent(): void
    {
        $expression = new NotHaveAttribute(Attribute::class);

        $class = new ClassDescriptionBuilder()
            ->setClassName('App\\Foo')
            ->setFilePath('/app/Foo.php')
            ->build();

        $violations = new Violations();

        $expression->evaluate($class, $violations, 'reasons');

        self::assertCount(0, $violations);
    }

    public function testEvaluateAddsViolationWhenAttributePresent(): void
    {
        $expression = new NotHaveAttribute(Attribute::class);

        $class = new ClassDescriptionBuilder()
            ->setClassName('App\\Foo')
            ->setFilePath('/app/Foo.php')
            ->addAttribute(Attribute::class, 1)
            ->build();

        $violations = new Violations();

        $expression->evaluate($class, $violations, 'reasons');

        self::assertCount(1, $violations);
        self::assertSame('App\\Foo', $violations->get(0)->getFqcn());
    }
}
