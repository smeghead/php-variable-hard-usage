# PHP CLI Tool for Analyzing Local Variable Usage

## Overview
This PHP CLI tool analyzes the usage of local variables in PHP source code, focusing on their scope and update frequency. It helps developers identify potential issues in handling local variables, improving code quality and maintainability.

![Testing](https://github.com/smeghead/php-variable-hard-usage/actions/workflows/php.yml/badge.svg?event=push) [![Latest Stable Version](https://poser.pugx.org/smeghead/php-variable-hard-usage/v)](https://packagist.org/packages/smeghead/php-variable-hard-usage) [![Total Downloads](https://poser.pugx.org/smeghead/php-variable-hard-usage/downloads)](https://packagist.org/packages/smeghead/php-variable-hard-usage) [![Latest Unstable Version](https://poser.pugx.org/smeghead/php-variable-hard-usage/v/unstable)](https://packagist.org/packages/smeghead/php-variable-hard-usage) [![License](https://poser.pugx.org/smeghead/php-variable-hard-usage/license)](https://packagist.org/packages/smeghead/php-variable-hard-usage) [![PHP Version Require](https://poser.pugx.org/smeghead/php-variable-hard-usage/require/php)](https://packagist.org/packages/smeghead/php-variable-hard-usage)

## What is "Local Variable Hard Usage"?
"Local Variable Hard Usage" is a concept that evaluates how intensely local variables are used in a function or method. This metric helps identify variables that might negatively impact code readability and maintainability due to excessive scope width and frequent updates.

The idea behind this metric is that when a local variable is referenced over a wide range of lines or is frequently modified, it becomes harder to understand and refactor. By quantifying this, we can gain insights into potential problem areas in the code.

This concept is introduced and explained in detail in the following blog post:
[Understanding Local Variable Hard Usage](https://blog.starbug1.com/archives/3022)

This tool analyzes PHP code using PHP-Parser to measure the "Local Variable Hard Usage" for each function and method. It calculates the average reference line span of each variable and sums the deviations from this average, providing a score that represents how heavily a variable is used within its scope.


## Features
- Analyzes local variable scope and update frequency.
- Provides insights into variable usage patterns.
- Helps identify potential issues related to variable handling.

## Installation
To install the PHP CLI tool, follow these steps:

1. Clone the repository:
    ```sh
    git clone https://github.com/smeghead/php-variable-hard-usage.git
    cd php-variable-hard-usage
    ```

2. Install dependencies using Composer:
    ```sh
    composer install
    ```

Alternatively, you can install the tool via Composer as a development dependency:
```sh
composer require --dev smeghead/php-variable-hard-usage
```

## Usage

If you specify the path of the file for which you want to measure the local variable abuse and run the program, a report will be displayed in JSON format.

```bash
$ vendor/bin/php-variable-hard-usage somewhere/your-php-file.php
{
    "maxVariableHardUsage": 65,
    "avarageVariableHardUsage": 26.833333333333332,
    "scopes": [
        {
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "VariableParser::__construct",
            "variableHardUsage": 1
        },
        {
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "VariableParser::resolveNames",
            "variableHardUsage": 9
        },
        {
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "VariableParser::parse",
            "variableHardUsage": 39
        },
        {
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "Expr_ArrowFunction@49",
            "variableHardUsage": 0
        },
        {
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "VariableParser::collectParseResultPerFunctionLike",
            "variableHardUsage": 65
        },
        {
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "Expr_Closure@65",
            "variableHardUsage": 47
        }
    ]
}
```

## How to calculate VariableHardUsage

VariableHardUsage is an index used to evaluate the frequency of use and scope of a local variable within a function. This indicator is calculated based on the variance of the line number at which the variable is used and the frequency with which the variable is assigned.

### Calculation Procedure

1. Obtain the line numbers of the variables:.

  * Obtains the line numbers of all variables used in the function.

2. Calculate the average of the line numbers.

  * Calculates the average of the retrieved line numbers. This is obtained by dividing the sum of the line numbers by the number of variables.

3. Calculate VariableHardUsage.

  * For each variable, the absolute difference between the line number and the average line number is calculated.
  * If a variable is assigned, the difference is multiplied by a factor (2 by default).
  * Sum all these values to obtain VariableHardUsage.

### EXAMPLE.

For example, suppose there are three variables in a function, each with row numbers 10, 20, and 30, and that some assignments are made and some are not made. In this case, the average row number is 20.

* Variable A: Row 10, with assignment
* Variable B: Row 20, no assignment
* Variable C: Row 30, with assignment

In this case, VariableHardUsage is calculated as follows

* Variable A: |10 - 20| * 2 = 20
* Variable B: |20 - 20| * 1 = 0
* Variable C: |30 - 20| * 2 = 20

Summing these, VariableHardUsage is 20 + 0 + 20 = 40.

VariableHardUsage is thus calculated as a measure of the frequency of use and scope of a variable. This metric can be used to quantitatively evaluate the usage of local variables within a function and help improve code readability and maintainability.
