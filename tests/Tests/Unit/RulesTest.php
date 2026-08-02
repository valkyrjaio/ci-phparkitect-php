<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja PHPArkitect package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Arkitect\Tests\Unit;

use Arkitect\CLI\Config;
use Valkyrja\Arkitect\Rules;
use Valkyrja\Arkitect\Tests\Abstract\ArkitectTestCase;

final class RulesTest extends ArkitectTestCase
{
    public function testGetRulesReturnsCallable(): void
    {
        self::assertIsCallable(Rules::getRules('src', 'tests'));
    }

    public function testGetRulesAddsSrcAndTestClassSets(): void
    {
        $config = new Config();

        $closure = Rules::getRules('src', 'tests');
        $closure($config);

        // One class set of rules for src, one for tests.
        self::assertCount(2, $config->getClassSetRules());
    }
}
