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
		$data = [3.141, 1.618, 1.234];
		$this->assertLessThan(1.9977, Stats::mean($data));
		$this->assertGreaterThan(1.9976, Stats::mean($data));

		$this->assertEquals(2, Stats::mean([1, 2, 3]));
		$this->assertEquals(273.125, Stats::mean([15, 1000, 68.5, 9]));

		$this->assertEquals(2, Stats::average([1, 2, 3]));
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

	public function testVariance(): void
	{
		$this->assertEquals(2.5, Stats::variance([1, 2, 3, 4, 5]));
		$this->assertEquals(2.5, Stats::variance([1, 2, 3, 4, 5], Stats::SAMPLE));
		$this->assertEquals(2, Stats::variance([1, 2, 3, 4, 5], Stats::POPULATION));
	}

	public function testStdev(): void
	{
		$this->assertEquals(1.5811388301, Stats::stdev([1, 2, 3, 4, 5]));
		$this->assertEquals(1.5811388301, Stats::stdev([1, 2, 3, 4, 5], Stats::SAMPLE));
		$this->assertEquals(1.4142135624, Stats::stdev([1, 2, 3, 4, 5], Stats::POPULATION));
	}

	public function testSterr(): void
	{
		$this->assertEquals(0.707106781187, Stats::sterr([1, 2, 3, 4, 5]));
		$this->assertEquals(0.707106781187, Stats::sterr([1, 2, 3, 4, 5], Stats::SAMPLE));
		$this->assertEquals(0.63245553203368, Stats::sterr([1, 2, 3, 4, 5], Stats::POPULATION));
	}
}
