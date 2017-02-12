<?php

require 'Stats.php';
$stats = new RichJenks\Stats;

var_dump($stats::intersect('x2-distributions.csv', 'DF', 5));
