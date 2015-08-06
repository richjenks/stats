<?php namespace RichJenks\ChiSquare;

class ChiSquare {

	/**
	 * @var array Observed data
	 */
	private $data;

	/**
	 * @var float Probability that results are caused by chance
	 */
	private $p;

	/**
	 * @var int Degrees of Freedom
	 */
	private $d;

	/**
	 * @var float Alpha: Critical Value
	 */
	private $a;

	/**
	 * @param array $observed observed data as tabular array
	 */
	public function __construct($data, $p = 0.05) {
		$this->data = $data;
		$this->p    = $p;
		$this->d    = $this->calc_dof($this->data);
		$this->a    = $this->intersect('critical-values.csv', $this->p, $this->d);
	}

	/**
	 * Calculates the Degrees of Freedom
	 *
	 * @param  array $data Observed data
	 * @return int   Number of Degrees of Freedom
	 */
	private function calc_dof($data) {
		$r = count($data) - 1;
		$c = count($data[0]) - 1;
		return $r*$c;
	}

	/**
	 * Finds the value of an intersection of a CSV file
	 * when given column and row headings
	 *
	 * @param string $file   Path to CSV file
	 * @param string $column Column heading to search by
	 * @param string $row    Row heading to search by
	 */
	function intersect($file, $column, $row) {

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

}