<p align="center"><a href="https://valkyrja.io" target="_blank">
    <img src="https://raw.githubusercontent.com/valkyrjaio/art/refs/heads/master/long-banner/orange/php.png" width="100%">
</a></p>

# Valkyrja PHPArkitect

Shared PHPArkitect configuration for Valkyrja PHP projects — architectural
rules that encode the naming and namespace conventions used across the
Valkyrja project, plus custom expressions that fill gaps in the built-in
PHPArkitect expression library.

<p>
    <a href="https://packagist.org/packages/valkyrja/ci-phparkitect"><img src="https://poser.pugx.org/valkyrja/ci-phparkitect/require/php" alt="PHP Version Require"></a>
    <a href="https://packagist.org/packages/valkyrja/ci-phparkitect"><img src="https://poser.pugx.org/valkyrja/ci-phparkitect/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/valkyrja/ci-phparkitect"><img src="https://poser.pugx.org/valkyrja/ci-phparkitect/license" alt="License"></a>
    <a href="https://github.com/valkyrjaio/ci-phparkitect-php/actions/workflows/ci.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/ci-phparkitect-php/actions/workflows/ci.yml/badge.svg?branch=26.x" alt="CI Status"></a>
    <a href="https://scrutinizer-ci.com/g/valkyrjaio/ci-phparkitect-php/?branch=26.x"><img src="https://scrutinizer-ci.com/g/valkyrjaio/ci-phparkitect-php/badges/quality-score.png?b=26.x" alt="Scrutinizer"></a>
    <a href="https://coveralls.io/github/valkyrjaio/ci-phparkitect-php?branch=26.x"><img src="https://coveralls.io/repos/github/valkyrjaio/ci-phparkitect-php/badge.svg?branch=26.x" alt="Coverage Status" /></a>
    <a href="https://shepherd.dev/github/valkyrjaio/ci-phparkitect-php"><img src="https://shepherd.dev/github/valkyrjaio/ci-phparkitect-php/coverage.svg" alt="Psalm Shepherd" /></a>
    <a href="https://sonarcloud.io/summary/new_code?id=valkyrjaio_phparkitect"><img src="https://sonarcloud.io/api/project_badges/measure?project=valkyrjaio_phparkitect&metric=sqale_rating" alt="Maintainability Rating" /></a>
</p>

Overview
--------

This repository contains two things:

1. **`Valkyrja\Arkitect\Rules`** — a reusable rule set that encodes the naming
   and namespace conventions used across the Valkyrja project. Drop it into
   any PHPArkitect config to enforce those conventions on your own code.
2. **Custom expressions** — PHPArkitect `Expression` implementations that
   fill gaps in the built-in expression library.

Installation
------------

```
composer require valkyrja/ci-phparkitect
```

Usage
-----

Reference `Rules::getRules()` from your `phparkitect.php` configuration file:

```
// phparkitect.php
use Valkyrja\Arkitect\Rules;

return Rules::getRules();
```

`getRules()` returns a closure that receives a PHPArkitect `Config` object
and registers all rules against two class sets:

- `src/` — source rules (naming, namespaces, architectural constraints)
- `tests/` — test rules (finality, naming conventions for test helpers)

The paths are resolved relative to the directory **three levels above**
`src/Arkitect/Rules.php` (i.e. the project root), matching the standard
Valkyrja monorepo layout.

Run PHPArkitect as normal:

```
vendor/bin/phparkitect check
```

Rules Reference
---------------

### Source Rules

#### No `@author` docblocks

All classes must not contain an `@author` docblock annotation.

#### Attributes

| Rule                                                                | Constraint                                   |
| ------------------------------------------------------------------- | -------------------------------------------- |
| Classes with `#[Attribute]` must reside in `*Attribute\` namespaces | Attributes belong in dedicated namespaces    |
| Classes in `*Attribute\` namespaces must carry `#[Attribute]`       | Only attributes live in attribute namespaces |
| Classes outside `*Attribute\*` must not carry `#[Attribute]`        | Non-attributes must not be attributed        |

#### Constants

| Rule                                                                              | Constraint           |
| --------------------------------------------------------------------------------- | -------------------- |
| Final classes (excluding `*Security` and `*Provider`) must reside in `*Constant\` | Constants namespace  |
| Classes in `*Constant\` must be final                                             | Constants are sealed |

#### Providers

| Type                        | Naming               | Namespace    |
| --------------------------- | -------------------- | ------------ |
| `ServiceProviderContract`   | `*ServiceProvider`   | `*Provider\` |
| `ComponentProviderContract` | `*ComponentProvider` | `*Provider\` |
| `CliRouteProviderContract`  | `*RouteProvider`     | `*Provider\` |
| `ListenerProviderContract`  | `*ListenerProvider`  | `*Provider\` |
| `HttpRouteProviderContract` | `*RouteProvider`     | `*Provider\` |
| All classes in `*Provider\` | `*Provider` suffix   | —            |

#### Factories

| Rule                                                | Constraint                                |
| --------------------------------------------------- | ----------------------------------------- |
| Classes named `*Factory` must reside in `*Factory\` | Factories belong in factory namespaces    |
| Classes in `*Factory\` must be named `*Factory`     | Only factories live in factory namespaces |

#### CLI Commands

Classes named `*Command` (outside `Valkyrja\Cli\*`, and not `*Handler` or
`*Middleware`) must reside in `*Cli\Command\` namespaces.

#### Security

Classes named `*Security` must reside in `*Security\` namespaces and must be
final.

#### Exceptions and Throwables

| Rule                                                                                  | Constraint                                       |
| ------------------------------------------------------------------------------------- | ------------------------------------------------ |
| Classes named `*Exception` must reside in `*Exception\`                               | Exceptions namespace                             |
| Classes extending `Throwable` must reside in `*Throwable\`                            | Throwable objects belong in throwable namespaces |
| Classes implementing `Throwable` must be named `*Exception`                           | Throwables are exceptions                        |
| Classes named `*RuntimeException` must reside in `*Abstract\` and be abstract         | Base runtime exceptions are abstract             |
| Classes named `*InvalidArgumentException` must reside in `*Abstract\` and be abstract | Base invalid argument exceptions are abstract    |

#### Types, Models, and Entities

| Rule                                                                                        | Constraint         |
| ------------------------------------------------------------------------------------------- | ------------------ |
| Classes extending `Type` (outside Config, Entity, Model namespaces) must reside in `*Type\` | Types namespace    |
| Classes extending `Model` (outside Config, Entity namespaces) must reside in `*Model\`      | Models namespace   |
| Classes extending `Entity` must reside in `*Entity\`                                        | Entities namespace |

#### Interfaces (Contracts)

| Rule                                                       | Constraint                    |
| ---------------------------------------------------------- | ----------------------------- |
| Interfaces must reside in `*Contract\` namespaces          | Contracts namespace           |
| Classes in `*Contract\` must be interfaces                 | Only interfaces are contracts |
| Interfaces (except `*Throwable`) must be named `*Contract` | Contract naming convention    |
| Classes named `*Contract` must be interfaces               | Naming implies interface      |

#### Traits

| Rule                                       | Constraint                           |
| ------------------------------------------ | ------------------------------------ |
| Traits must reside in `*Trait\` namespaces | Traits namespace                     |
| Classes in `*Trait\` must be traits        | Only traits live in trait namespaces |
| Traits must not be named `*Trait*`         | No "Trait" in the name               |
| All classes must not be named `*Trait*`    | No "Trait" in any name               |

#### Abstract Classes

| Rule                                                                                                         | Constraint                       |
| ------------------------------------------------------------------------------------------------------------ | -------------------------------- |
| Abstract classes (outside `*Factory` and specific routing controller namespaces) must reside in `*Abstract\` | Abstract namespace               |
| Classes in `*Abstract\` must be abstract                                                                     | Only abstract classes live there |
| Abstract classes must not be named `*Abstract*`                                                              | No "Abstract" in the name        |
| Non-abstract classes must not be named `*Abstract*`                                                          | No "Abstract" in any name        |

#### Enums

| Rule                                                                                                 | Constraint                         |
| ---------------------------------------------------------------------------------------------------- | ---------------------------------- |
| Enums must reside in `*Enum\` namespaces                                                             | Enums namespace                    |
| Classes in `*Enum\` (outside `Valkyrja\Type\Enum`) must be enums                                     | Only enums live in enum namespaces |
| Enums must not be named `*Enum*`                                                                     | No "Enum" in enum names            |
| Non-enums (outside `Valkyrja\Type\Enum`, excluding `EnhancedEnumSupport`) must not be named `*Enum*` | No "Enum" in non-enum names        |

### Test Rules

| Rule                                                                                                                                  | Constraint                         |
| ------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------- |
| All classes must not contain `@author` docblocks                                                                                      | Same as src                        |
| Test classes (in `*Tests\`, not `*TestCase`, `*AttributeClass`, `*EnvClass`, non-abstract, non-trait, outside `Vendor`) must be final | Tests are sealed                   |
| Classes in `*Classes\` (non-enum, non-trait) must be named `*Class`                                                                   | Test helper class naming           |
| Classes in `*Classes\` must not be named `*Test`                                                                                      | Test helpers are not tests         |
| Classes not named `*Test` must not reside in `*Unit\` or `*Functional\`                                                               | Only tests live in test namespaces |
| Traits (not `TestCase`) in tests must reside in `*Trait\` and be named `*Trait`                                                       | Test trait conventions             |

Custom Expressions
------------------

### `NotHaveAttribute`

`Valkyrja\Arkitect\Expression\ForClasses\NotHaveAttribute`

The inverse of PHPArkitect's built-in `HaveAttribute`. Evaluates to a
violation when the target class **does** carry the specified PHP attribute.

```
use Valkyrja\Arkitect\Expression\ForClasses\NotHaveAttribute;

Rule::allClasses()
    ->that(new NotResideInTheseNamespaces('*Attribute\\*'))
    ->should(new NotHaveAttribute(Attribute::class))
    ->because('Non-attribute classes must not carry #[Attribute]');
```

**Constructor:**

```
new NotHaveAttribute(string $attribute)
```

| Parameter    | Description                                              |
| ------------ | -------------------------------------------------------- |
| `$attribute` | Fully qualified class name of the PHP attribute to check |

**Violation message format:**

```
<FQCN> should not have the attribute <attribute> because <reason>
```

Workflows
---------

The [`_workflow-call.yml`](.github/workflows/_workflow-call.yml) reusable
workflow runs PHPArkitect against the calling repository's source. It is
designed to be called from other repositories via `workflow_call`.

### Inputs

| Input              | Type    | Default                    | Description                                                                                                                                           |
| ------------------ | ------- | -------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| `paths`            | string  | —                          | **Required.** YAML filter spec with two keys: `ci` (CI config files that trigger a base-branch fetch) and `files` (all files that trigger the check). |
| `post-pr-comment`  | boolean | `true`                     | Post a PR comment on failure and remove it on success. Disable when the calling workflow handles its own reporting.                                   |
| `composer-options` | string  | `''`                       | Extra flags passed to every `composer install` step (e.g. `--ignore-platform-req=ext-openswoole`).                                                    |
| `php-version`      | string  | `'8.4'`                    | PHP version to use.                                                                                                                                   |
| `ci-directory`     | string  | `'.github/ci/phparkitect'` | Path to the CI directory containing `composer.json` and the tool config.                                                                              |
| `extensions`       | string  | `'mbstring, intl'`         | PHP extensions to install via `shivammathur/setup-php`.                                                                                               |

### Usage

```yaml
jobs:
  phparkitect:
    uses: valkyrjaio/ci-phparkitect-php/.github/workflows/_workflow-call.yml@26.x
    permissions:
      pull-requests: write
      contents: read
    with:
      php-version: '8.4'
      paths: |
        ci:
          - '.github/ci/phparkitect/**'
          - '.github/workflows/phparkitect.yml'
        files:
          - '.github/ci/phparkitect/**'
          - '.github/workflows/phparkitect.yml'
          - 'src/**/*.php'
          - 'composer.json'
    secrets: inherit
```

`secrets: inherit` is required to pass the `VALKYRJA_GHA_APP_ID` and
`VALKYRJA_GHA_PRIVATE_KEY` org secrets used for PR comments.

Contributing
------------

See [`CONTRIBUTING.md`][contributing url] for the submission process and
[`VOCABULARY.md`][vocabulary url] for the terminology used across Valkyrja.

Security Issues
---------------

If you discover a security vulnerability, please follow our
[disclosure procedure][security vulnerabilities url].

License
-------

Licensed under the [MIT license][MIT license url]. See
[`LICENSE.md`](./LICENSE.md).

[contributing url]: https://github.com/valkyrjaio/.github/blob/26.x/CONTRIBUTING.md
[vocabulary url]: https://github.com/valkyrjaio/.github/blob/26.x/VOCABULARY.md
[security vulnerabilities url]: https://github.com/valkyrjaio/.github/blob/26.x/SECURITY.md
[MIT license url]: https://opensource.org/licenses/MIT
