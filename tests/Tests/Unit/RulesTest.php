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
