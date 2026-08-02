<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja PHPArkitect package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Arkitect\Tests\Unit\Expression\ForClasses;

use Arkitect\Analyzer\ClassDescription;
use Arkitect\Analyzer\ClassDescriptionBuilder;
use Arkitect\Rules\Violations;
use Valkyrja\Arkitect\Expression\ForClasses\HaveNameContainingComponent;
use Valkyrja\Arkitect\Tests\Abstract\ArkitectTestCase;

final class HaveNameContainingComponentTest extends ArkitectTestCase
{
    private static function classDescription(string $fqcn): ClassDescription
    {
        return new ClassDescriptionBuilder()
            ->setClassName($fqcn)
            ->setFilePath('/src/Provider.php')
            ->build();
    }

    private static function evaluate(string $fqcn): Violations
    {
        $violations = new Violations();

        new HaveNameContainingComponent()->evaluate(self::classDescription($fqcn), $violations, 'reasons');

        return $violations;
    }

    public function testDescribeNamesTheComponent(): void
    {
        $expression = new HaveNameContainingComponent();

        $class = self::classDescription('Valkyrja\\Session\\Provider\\SessionServiceProvider');

        self::assertSame(
            'should have a name that contains the component name Session because reasons',
            $expression->describe($class, 'reasons')->toString(),
        );
    }

    public function testDescribeOmitsTheComponentWithoutANamespace(): void
    {
        $expression = new HaveNameContainingComponent();

        $class = self::classDescription('SessionServiceProvider');

        self::assertSame(
            'should have a name that contains its component name because reasons',
            $expression->describe($class, 'reasons')->toString(),
        );
    }

    public function testEvaluateAddsNoViolationWithoutANamespace(): void
    {
        $violations = self::evaluate('SessionServiceProvider');

        self::assertCount(0, $violations);
    }

    public function testEvaluateAddsNoViolationWhenTheNameContainsTheComponent(): void
    {
        $violations = self::evaluate('Valkyrja\\Session\\Provider\\SessionServiceProvider');

        self::assertCount(0, $violations);
    }

    public function testEvaluateAddsNoViolationWhenASubComponentNestsTheComponent(): void
    {
        $violations = self::evaluate('Valkyrja\\Http\\Routing\\Provider\\HttpRoutingHttpRoutesProvider');

        self::assertCount(0, $violations);
    }

    public function testEvaluateAddsNoViolationWhenAQualifierPrecedesTheComponent(): void
    {
        $violations = self::evaluate('Valkyrja\\Application\\Provider\\CliApplicationComponentProvider');

        self::assertCount(0, $violations);
    }

    public function testEvaluateAddsNoViolationWhenTheRootNamesTheComponent(): void
    {
        $violations = self::evaluate('Sindri\\Provider\\SindriComponentProvider');

        self::assertCount(0, $violations);
    }

    public function testEvaluateAddsViolationWhenTheNameOmitsTheComponent(): void
    {
        $violations = self::evaluate('Valkyrja\\Session\\Provider\\SmsServiceProvider');

        self::assertCount(1, $violations);
        self::assertSame('Valkyrja\\Session\\Provider\\SmsServiceProvider', $violations->get(0)->getFqcn());
    }

    public function testEvaluateAddsViolationWhenTheRootDoesNotNameTheComponent(): void
    {
        $violations = self::evaluate('Sindri\\Provider\\SmsComponentProvider');

        self::assertCount(1, $violations);
        self::assertSame('Sindri\\Provider\\SmsComponentProvider', $violations->get(0)->getFqcn());
    }
}
