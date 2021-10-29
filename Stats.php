<?php

declare(strict_types=1);

namespace RichJenks\Stats;

/**
 * PHP Statistics Library for non-statisticians
 */
class Stats
{

	// Constants for method options
	const SAMPLE     = 0;
	const POPULATION = 1;

	/**
	 * Calculates the Mean of given data
	 *
	 * @param  array $data Array of values
	 * @return float Calculated Mean
	 */
	public static function mean(array $data): float
	{
		return array_sum($data) / count($data);
	}

	/**
	 * Alias for Mean, usage is the same
	 *
	 * @param  array $data Array of values
	 * @return float Calculated Mean
	 */
	public static function average(array $data): float
	{
		return self::mean($data);
	}

	/**
	 * Calculates the Median of given data
	 *
	 * @param  array $data Array of values
	 * @return float Calculated Median
	 */
	public static function median(array $data): float
	{
		sort($data);
		$count = count($data);
		$i = round($count / 2) - 1;
		if ($count % 2 !== 0) return $data[$i];
		else return self::mean([$data[$i], $data[$i + 1]]);
	}

	/**
	 * Calculates the Mode of given data
	 *
	 * @param  array $data Array of values
	 * @return array Mode(s)
	 */
	public static function mode(array $data): array
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
	 * @param  array $data Array of values
	 * @return array Array of values and frequencies
	 */
	public static function frequencies(array $data): array
	{
		$frequencies = array_count_values($data);
		arsort($frequencies);
		return $frequencies;
	}

	/**
	 * Determines the Range of given data
	 *
	 * @param  array $data Array of values
	 * @return float Calculated Range
	 */
	public static function range(array $data): float
	{
		return max($data) - min($data);
	}

	/**
	 * Determines the deviations of each value of given data
	 *
	 * @param array $data Array of values
	 * @return array Values as keys and deviations as values
	 */
	public static function deviations(array $data): array
	{
		$mean = self::mean($data);

		$deviations = [];
		foreach ($data as $key => $value) {
			$deviations[(string) $value] = pow($value - $mean, 2);
		}

		return $deviations;
	}

	/**
	 * Determines the Variance of given data
	 *
	 * @param array $data Array of values
	 * @param int   $type Whether the data is a sample or whole population
	 *
	 * @return float Calculated Variance
	 */
	public static function variance(array $data, int $type = self::SAMPLE): float
	{
		$deviations = self::deviations($data);

		// Must sum and count, etc. rather than simply calculate mean
		// to accommodate for sample/variance option
		$sum      = array_sum($deviations);
		$count    = count($deviations);
		$divide   = ($type === self::SAMPLE) ? $count - 1 : $count;
		$variance = $sum / $divide;

		return $variance;
	}

	/**
	 * Determines the Standard Deviation of given data
	 *
	 * @param array $data Array of values
	 * @param int   $type Whether the data is a sample or whole population
	 *
	 * @return float Calculated Standard Deviation
	 */
	public static function sd(array $data, int $type = self::SAMPLE): float
	{
		return sqrt(self::variance($data, $type));
	}

	/**
	 * Determines the Standard Error of the Mean
	 *
	 * @param  array $data Array of values
	 * @return float Calculated Standard Error
	 */
	public static function sem(array $data): float
	{
		$sd    = self::sd($data);
		$count = count($data);
		$sem   = $sd / sqrt($count);

		return $sem;
	}

	/**
	 * Calculates the Quartiles of given data
	 *
	 * @param  array $data Array of values
	 * @return array Quartiles 0–4 in order
	 */
	public static function quartiles(array $data): array
	{
		sort($data);

		$q = [];

		$q[0] = min($data);
		$q[2] = self::median($data);
		$q[4] = max($data);

		// If odd numbers of data points, remove middle one
		if (count($data) % 2 !== 0) unset($data[round(count($data) / 2)]);

		// Get halves of data
		$chunks = array_chunk($data, count($data) / 2);

		// Calculate 1st and 3rd quartiles
		$q[1] = self::median($chunks[0]);
		$q[3] = self::median($chunks[1]);

		ksort($q);
		return $q;
	}

	/**
	 * Calculates the Interquartile Range of given data
	 *
	 * @param  array $data Array of values
	 * @return array Calculated Interquartile Range
	 */
	public static function iqr(array $data): float
	{
		$quartiles = self::quartiles($data);
		return $quartiles[3] - $quartiles[1];
	}

	/**
	 * Determines the lower and upper limit for outliers
	 *
	 * @param array $data Array of values
	 * @return array 0 => lower limit, 1 => upper limit
	 */
	public static function whiskers(array $data): array
	{
		$q     = self::quartiles($data);
		$iqr   = self::iqr($data);
		$lower = $q[1] - ($iqr * 1.5);
		$upper = $q[3] + ($iqr * 1.5);

		return ['lower' => $lower, 'upper' => $upper];
	}

	/**
	 * Determines which values in a series are outliers
	 *
	 * @param  array $data Array of values
	 * @return array Array of outliers
	 */
	public static function outliers(array $data): array
	{
		$whiskers = self::whiskers($data);
		extract($whiskers);

		$outliers = [];
		foreach ($data as $value) {
			if ($value < $lower || $value > $upper) {
				$outliers[] = $value;
			}
		}

		return $outliers;
	}

	/**
	 * Determines which values in a series are not outliers
	 *
	 * @param  array $data Array of values
	 * @return array Array of inliers
	 */
	public static function inliers(array $data): array
	{
		$whiskers = self::whiskers($data);
		extract($whiskers);

		$inliers = [];
		foreach ($data as $value) {
			if ($value >= $lower && $value <= $upper) {
				$inliers[] = $value;
			}
		}

		return $inliers;
	}

	/**
	 * Determines the percentiles of each value in a range
	 *
	 * @param array $data Array of values
	 * @param int   $rount Number of decimal places to round results to, negative for no rounding
	 *
	 * @return array Values as keys and percentiles as values
	 */
	public static function percentiles(array $data, int $round = 0): array
	{
		sort($data);

		$min  = min($data);
		$step = 100 / self::range($data);

		$percentiles = [];
		foreach ($data as $value) {
			$percentile = ($value - $min) * $step;
			if ($round >= 0) {
				$percentile = round($percentile, $round);
			}
			$percentiles[$value] = $percentile;
		}

		return $percentiles;
	}

	/**
	 * Determines the value at the given percentile in a range
	 *
	 * @param array $data       Array of values
	 * @param float $percentile Percentile to find based on Closest Rank
	 *
	 * @return array Closest value as key and exact percentile as value
	 */
	public static function percentile(array $data, float $percentile, int $round = 0): array
	{
		$percentiles = self::percentiles($data, $round);
		$closest     = ['value' => null, 'percentile' => null];

		foreach ($percentiles as $value => $value_percentile) {
			if ($closest['value'] === null) {
				$closest['value']      = $value;
				$closest['percentile'] = $value_percentile;
			} else {
				$max_closest = max([$closest['percentile'], $percentile]);
				$min_closest = min([$closest['percentile'], $percentile]);

				$max_search = max([$value_percentile, $percentile]);
				$min_search = min([$value_percentile, $percentile]);

				if ($max_closest - $min_closest > $max_search - $min_search) {
					$closest['value']      = $value;
					$closest['percentile'] = $value_percentile;
				}
			}
		}

		return $closest;
	}

	/**
	 * Determines the values in the given data that fall in the given percentile
	 *
	 * @param array $data       Array of values
	 * @param float $percentile Percentile to search for
	 *
	 * @return array Values as keys and percentiles as values
	 */
	public static function intrapercentile(array $data, float $percentile, int $round = 0): array
	{
		$percentiles = self::percentiles($data, $round);
		$intrapercentile = [];
		foreach ($percentiles as $value => $value_percentile) {
			if ($percentile >= $value_percentile) {
				$intrapercentile[$value] = $value_percentile;
			}
		}
		return $intrapercentile;
	}

}
