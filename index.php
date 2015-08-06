<?php

$data = [
	[2100, 1900],
	[1100, 900],
];

require 'ChiSquare.php';
$chi = new RichJenks\ChiSquare\ChiSquare($data);
