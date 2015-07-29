# Chi Square

Test of statistical significance for discrete data.

## Usage

1. Construct array of data that looks like a table

    ```php
    $data = [
        [2100, 1900],
        [1100, 900],
    ];
    ```

1. Pass to class constructor

    ```php
    require 'ChiSquare.php';
    $chi2 = new RichJenks\ChiSquare\ChiSquare($observed, 0.75);
    ```

    `p` is an optional second parameter defaulting to `0.5`
