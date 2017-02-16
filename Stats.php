<?php

declare(strict_types=1);

namespace RichJenks;

/**
 * PHP Statistics Library for non-statisticians
 *
 * @todo Write unit tests
 */
class Stats
{

	// Constants for method options
	const SAMPLE     = 0;
	const POPULATION = 1;

	/**
	 * Calculates the mean of given data
	 *
	 * @param  array $data Array of numbers
	 * @return float Calculated mean
	 */
	public static function mean(array $data) : float
	{
		return array_sum($data) / count($data);
	}

	/**
	 * Alias for mean, usage is the same
	 *
	 * @param  array $data Array of numbers
	 * @return float Calculated mean
	 */
	public static function average(array $data) : float
	{
		return self::mean($data);
	}

	/**
	 * Calculates the median of given data
	 *
	 * @param  array $data Array of numbers
	 * @return float Calculated median
	 */
	public static function median(array $data) : float
	{
		sort($data);
		$count = count($data);
		$i = round($count / 2) - 1;
		if ($count % 2 !== 0) return $data[$i];
		else return self::mean([$data[$i], $data[$i + 1]]);
	}

	/**
	 * Calculates the mode of given data
	 *
	 * @todo Support multiple or no modes using `self::frequency()`
	 *
	 * @param  array $data Array of numbers
	 * @return array Mode(s)
	 */
	public static function mode(array $data) : array
	{
		// Frequency of each value
		$data = self::frequencies($data);
		// Array for holding confirmed modes
		$modes = [];
		// First option is a mode because it's first in frequency array
		$modes[] = key($data);
		// Store the frequency of the first value for later comparison
		$max = $data[key($data)];
		// Remove the first item because it's already added to modes
		unset($data[key($data)]);
		// Set to true if a value is lower than the previous one
		$found = false;

		// Iterate through values to see if each one is a mode
		foreach ($data as $value => $frequency) {
			if ($frequency === $max) {
				$modes[] = $value;
			} else {
				$found = true;
				break;
			}
		}

		return ($found) ? $modes : [];

	}

	/**
	 * Constructs a sorted array of frequencies for each value in a series
	 *
	 * @param  array $data Array of numbers
	 * @return array Array of numbers and frequencies
	 */
	public function frequencies(array $data) : array
	{
		$frequencies = array_count_values($data);
		arsort($frequencies);
		return $frequencies;
	}

	/**
	 * Determines the range of given data
	 *
	 * @param  array $data Array of numbers
	 * @return float Calculated range
	 */
	public static function range(array $data) : float
	{
		return max($data) - min($data);
	}

	/**
	 * Determines the variance of given data
	 *
	 * @param array $data Array of numbers
	 * @param int   $type Whether the data is a sample or whole population
	 *
	 * @return float Calculated variance
	 */
	public static function variance(array $data, int $type = self::SAMPLE) : float
	{
		$mean = self::mean($data);

		foreach ($data as $key => $value) {
			$data[$key] = $value - $mean;
			$data[$key] = pow($data[$key], 2);
		}

		$sum      = array_sum($data);
		$count    = count($data);
		$divide   = ($type === self::SAMPLE) ? $count - 1 : $count;
		$variance = $sum / $divide;

		return $variance;
	}

	/**
	 * Determines the standard deviation of given data
	 *
	 * @param array $data Array of numbers
	 * @param int   $type Whether the data is a sample or whole population
	 *
	 * @return float Calculated standard deviation
	 */
	public static function stdev(array $data, int $type = self::SAMPLE) : float
	{
		return sqrt(self::variance($data, $type));
	}

	/**
	 * Determines the standard error of given data
	 *
	 * @param array $data Array of numbers
	 * @param int   $type Whether the data is a sample or whole population
	 *
	 * @return float Calculated standard error
	 */
	public static function sterr(array $data, int $type = self::SAMPLE) : float
	{
		$stdev   = self::stdev($data, $type);
		$count   = count($data);
		$divide  = ($type === self::SAMPLE) ? $count - 1 : $count;
		$sterror = $stdev / sqrt($count);

		return $sterror;
	}

}
