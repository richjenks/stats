<?php namespace RichJenks;

class Stats {

	/**
	 * Finds the value of an intersection of a CSV file
	 * when given column and row headings
	 *
	 * @param string $file   Path to CSV file
	 * @param string $column Column heading to search by
	 * @param string $row    Row heading to search by
	 *
 	 * @return mixed Value at intersection or NULL if not found
	 */
	public static function intersect($file, $column, $row) {

		// All cells will be strings
		$column = (string) $column;
		$row    = (string) $row;

		// Get table
		$table = array_map('str_getcsv', file($file));

		// Get headings and flip so values match row keys
		$headings = array_shift($table);
		array_shift($headings); // First is empty!
		$headings = array_flip($headings);

		// Move row headings to array keys
		foreach ($table as $key => $value) $rows[array_shift($value)] = $value;

		// Get column key, if exists
		if (!isset($headings[$column])) return null;
		$key = $headings[$column];

		// Get intersection value, if exists
		if (!isset($rows[$row][$key])) return null;
		$value = $rows[$row][$key];

		// Attempt to cast to a sensible type
		if (in_array(strtolower($value), ['true', 'false'])) $value = (bool) $value;
		if (is_numeric($value)) $value = $value+0;

		return $value;

	}

	/**
	 * Calculates the mean of given data
	 *
	 * @param  array     $data Array of numbers
	 * @return int|float Calculated mean
	 */
	public static function mean($data) {
		$sum = array_sum($data);
		$num = count($data);
		return $sum / $num;
	}

	/**
	 * Calculates the median of given data
	 *
	 * @param  array     $data Array of numbers
	 * @return int|float Calculated median
	 */
	public static function median($data) {
		sort($data);
		$count = count($data);
		$i = round($count / 2) - 1;
		if ($count % 2 !== 0) return $data[$i];
		else return self::mean([$data[$i], $data[$i + 1]]);
	}

	/**
	 * Calculates the mode of given data
	 *
	 * @todo Support multiple or no modes
	 *
	 * @param  array     $data Array of numbers
	 * @return int|float Calculated mode
	 */
	public static function mode($data) {
		$counts = [];
		foreach ($data as $value)
			isset($counts[$value]) ? $counts[$value]++ : $counts[$value] = 1;
		arsort($counts);
		reset($counts);
		return key($counts);
	}

}
