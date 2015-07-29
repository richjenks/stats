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
	 * @var float Alpha: Chi Square value
	 */
	private $a;

	/**
	 * @param array $observed observed data as tabular array
	 */
	public function __construct($data, $p = 0.05) {

		$this->data = $data;
		$this->p = $p;
		$this->d = $this->calc_dof();

		var_dump($this->data);
		var_dump($this->p);
		var_dump($this->d);

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
	 * Determines the alpha for Chi Square
	 *
	 * @param float $p Probability of chance
	 * @param int   $d Degrees of Freedom
	 */
	private static function calc_alpha($p, $d) {
		$alphas = [
			1
		];
	}

}