# Stats

A statistics library for non-statistical people

## Introduction

If you're into statistics then PHP will not be your language of choice (try [R](https://www.r-project.org/) instead) but if for any reason you, a non-statistician, need to _do some stats_ then this library aims to provide a simple set of methods for common statistical functions.

Many of the methods in this library are available from the [Statistics Extension](http://uk1.php.net/manual/en/book.stats.php), however this is not included in PHP by default. If possible, I'd recommend using this extension rather than my stats library.

## Requirements

PHP 7.2 and PDO SQLite

## Getting Started

1. Install with Composer: `composer require richjenks/stats`
1. Include autoloader: `require 'vendor/autoload.php';`
1. All static methods are available from the `RichJenks\Stats` class

## Example

```php
<?php
require 'vendor/autoload.php';
use RichJenks\Stats;
echo Stats::mean([1, 2, 3]);
// 2
```

> Stats will generally return either a `float` or an `array`, whichever is most appropriate for the function

## Usage

### Mean/Average

Calculates the mean of given data:

```php
Stats::mean([1, 2, 3]);
// 2

Stats::mean([15, 1000, 68.5, 9]);
// 273.125
```

> The `average` function aliases `mean`, e.g. `Stats::average([1, 2, 3]);` also returns `2`

### Median

Calculates the median of given data:

```php
Stats::median([1, 2, 3]);
// 2

Stats::median([3.141, 1.618, 1.234]);
// 1.618
```

### Mode

Calculates the mode(s) of given data:

```php
Stats::mode([1, 2, 2, 3]);
// [2]

`Stats::mode([1, 2, 2, 3, 3]);
// [2, 3]
```

> This function always returns an array because it is able to handle multi-modal data — an empty array would mean there is no mode

### Frequencies

Constructs a sorted array of frequencies for each value in a series:

```php
Stats::frequencies([1, 2, 3])
// [1 => 1, 2 => 1, 3 => 1]

Stats::frequencies([10, 20, 20])
// [20 => 2, 10 => 1]
```

### Range

Determines the range of given data:

```php
Stats::range([1, 9])
// 8

Stats::range([-41, 1.61803])
// 42.61803
```

### Variance, Standard Deviation & Standard Error

Determines the variance/standard deviation/standard deviation of given data:

```php
Stats::variance([1, 2, 3, 4, 5]);
// 2.5

Stats::stdev([1, 2, 3, 4, 5]);
// 1.5811388301

Stats::sterr([1, 2, 3, 4, 5]);
// 0.707106781187
```

#### Sample or Population

> If you don't understand this bit you probably don't need it :)

You can optionally pass the constants `Stats::SAMPLE` (default) or `Stats::POPULATION` as second parameters to determine whether your data is for a sample or a whole population:

```php
Stats::variance([1, 2, 3, 4, 5], Stats::POPULATION)
// 2

Stats::stdev([1, 2, 3, 4, 5], Stats::POPULATION)
// 1.4142135624

Stats::sterr([1, 2, 3, 4, 5], Stats::POPULATION)
// 0.70710678118655
```

## Running tests

```bash
phpunit --bootstrap Stats.php tests/StatsTest -v
```
