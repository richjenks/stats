<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use RichJenks\Stats;

/**
 * @covers Stats
 */
final class StatsTest extends TestCase
{
	public function testMean(): void
	{
		$this->assertEquals(2, Stats::average([1, 2, 3]));
		$this->assertEquals(2, Stats::mean([1, 2, 3]));
		$this->assertEquals(1.99766667, round(Stats::mean([3.141, 1.618, 1.234]), 8));
	}

	public function testMedian(): void
	{
		$this->assertEquals(2, Stats::median([1, 2, 3]));
		$this->assertEquals(2.5, Stats::median([1, 2, 3, 4]));

		$data = [3.141, 1.618, 1.234];
		$this->assertEquals(1.618, Stats::median($data));

		$data = [1, 2, 3, 4];
		$this->assertEquals(2.5, Stats::median($data));
	}

	public function testMode(): void
	{
		$this->assertEquals([],  Stats::mode([1, 2, 3]));
		$this->assertEquals([],  Stats::mode([1, 1, 1]));
		$this->assertEquals([2], Stats::mode([1, 2, 2]));

		$data = [1, 2, 2, 3, 3];
		$this->assertContains(2, Stats::mode($data));
		$this->assertContains(3, Stats::mode($data));
	}

	public function testFrequencies(): void
	{
		$this->assertEquals([
			1 => 1,
			2 => 1,
			3 => 1,
		], Stats::frequencies([1, 2, 3]));

		$this->assertEquals([
			2 => 2,
			1 => 1,
			3 => 1,
		], Stats::frequencies([1, 2, 3, 2]));
	}

	public function testRange(): void
	{
		$this->assertEquals(8, Stats::range([1, 9]));
		$this->assertEquals(42.61803, Stats::range([-41, 1.61803]));
	}

	public function testDeviations(): void
	{
		$this->assertEquals(
			[1 => 4, 2 => 1, 3 => 0, 4 => 1, 5 => 4],
			Stats::deviations([1, 2, 3, 4, 5])
		);
		$this->assertEquals(
			[42 => 94.09, 75 => 542.89, 101 => 2430.49, 22.5 => 852.64, 18 => 1135.69],
			Stats::deviations([42, 75, 101, 22.5, 18])
		);
	}

	public function testVariance(): void
	{
		$this->assertEquals(2.5, Stats::variance([1, 2, 3, 4, 5]));
		$this->assertEquals(2.5, Stats::variance([1, 2, 3, 4, 5], Stats::SAMPLE));
		$this->assertEquals(2, Stats::variance([1, 2, 3, 4, 5], Stats::POPULATION));
	}

	public function testSd(): void
	{
		$this->assertEquals(1.58113883, round(Stats::sd([1, 2, 3, 4, 5]), 8));
		$this->assertEquals(1.58113883, round(Stats::sd([1, 2, 3, 4, 5], Stats::SAMPLE), 8));
		$this->assertEquals(1.41421356, round(Stats::sd([1, 2, 3, 4, 5], Stats::POPULATION), 8));
	}

	public function testSem(): void
	{
		$this->assertEquals(0.70710678, round(Stats::sem([1, 2, 3, 4, 5]), 8));
	}

	public function testQuartiles(): void
	{
		$this->assertEquals(
			[0 => 1, 1 => 3.5, 2 => 6.5, 3 => 9.5, 4 => 12],
			Stats::quartiles([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])
		);
		$this->assertEquals(
			[0 => 1, 1 => 3.5, 2 => 7, 3 => 10.5, 4 => 13],
			Stats::quartiles([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])
		);
		$this->assertEquals(
			[0 => 443, 1 => 560, 2 => 725.5, 3 => 839, 4 => 930],
			Stats::quartiles([839, 560, 607, 828, 875, 805, 646, 450, 930, 443])
		);
	}

	public function testIqr(): void
	{
		$this->assertEquals(6, Stats::iqr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]));
		$this->assertEquals(7, Stats::iqr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]));
		$this->assertEquals(279, Stats::iqr([839, 560, 607, 828, 875, 805, 646, 450, 930, 443]));
	}

	public function testOutliers(): void
	{
		$this->assertEquals([999], Stats::outliers([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 999]));
	}
}
