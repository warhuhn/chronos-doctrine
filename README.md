# chronos-doctrine

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/warhuhn/chronos-doctrine/blob/master/LICENSE)
[![Run unit tests](https://github.com/warhuhn/chronos-doctrine/actions/workflows/run-unit-tests.yml/badge.svg)](https://github.com/warhuhn/chronos-doctrine/actions/workflows/run-unit-tests.yml)

The warhuhn/chronos-doctrine library adds Doctrine DBAL Types that convert Date/DateTime-based database values to
Immutable Chronos DateTime-Implementations.

## Installation

```bash
composer.phar require warhuhn/chronos-doctrine
```

## Configuration

### doctrine/dbal in raw PHP

```php
<?php

\Doctrine\DBAL\Types::addType('chronos_date', \Warhuhn\Doctrine\DBAL\Types\ChronosDateType::class);
\Doctrine\DBAL\Types::addType('chronos_datetime', \Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeType::class);
\Doctrine\DBAL\Types::addType('chronos_datetimetz', \Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeTzType::class);
```

### Symfony

```yaml
# app/config/config.yml
doctrine:
   dbal:
       types:
           chronos_date: Warhuhn\Doctrine\DBAL\Types\ChronosDateType
           chronos_datetime: Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeType
           chronos_datetimetz: Warhuhn\Doctrine\DBAL\Types\ChronosDateTimeTzType
```

## Usage in Doctrine ORM 3.0

```php
<?php

namespace Warhuhn\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosDate;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Example
{

    #[ORM\Column(type: 'chronos_date')]
    private ChronosDate $date;

    #[ORM\Column(type: 'chronos_datetime')]
    private Chronos $dateTime;

    #[ORM\Column(type: 'chronos_datetimetz')]
    private Chronos $dateTimeTz;

}
```