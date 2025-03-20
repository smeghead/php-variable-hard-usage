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

The tool provides two operation modes: `single` mode for analyzing individual files and `scopes` mode for analyzing multiple files or directories.

### Single File Analysis

Use the `single` command to analyze a single PHP file:

```bash
$ vendor/bin/php-variable-hard-usage single path/to/your-file.php
{
    "filename": "path/to/your-file.php",
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
        }
    ]
}
```

For backward compatibility, you can also run without specifying the `single` command:

```bash
$ vendor/bin/php-variable-hard-usage path/to/your-file.php
```

### Multiple Files Analysis

Use the scopes command to analyze multiple files or entire directories:

```bash
# Analyze all PHP files in a directory
$ vendor/bin/php-variable-hard-usage scopes src/

# Analyze multiple directories
$ vendor/bin/php-variable-hard-usage scopes src/ tests/

# Analyze specific files and directories
$ vendor/bin/php-variable-hard-usage scopes src/Command.php config/ tests/
```

The output for scopes mode is a combined report with results sorted by variable hard usage:

```json
{
    "scopes": [
        {
            "file": "src/Parse/VariableParser.php",
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "VariableParser::collectParseResultPerFunctionLike",
            "variableHardUsage": 65
        },
        {
            "file": "src/Parse/VariableParser.php",
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "Expr_Closure@65",
            "variableHardUsage": 47
        },
        {
            "file": "src/Parse/VariableParser.php",
            "namespace": "Smeghead\\PhpVariableHardUsage\\Parse",
            "name": "VariableParser::parse",
            "variableHardUsage": 39
        }
    ]
}
```

### Help and Version Information

To display help information:

```bash
$ vendor/bin/php-variable-hard-usage --help
```

```bash
$ vendor/bin/php-variable-hard-usage --version
```

## How to calculate VariableHardUsage

VariableHardUsage is an index used to evaluate the frequency of use and scope of local variables within a function. This measure is calculated based on the variance of the line number of references to the same variable and the frequency with which the variable is assigned.

### Calculation Procedure

1. Obtain the line numbers of the references to the same variable:.

  * For each variable used in the function, retrieve all line numbers where the variable is referenced.

2. Calculate deviation based on first occurrence.

  * For each reference, computes the difference between the line number and the line number of the first occurrence of the variable.

3. Applying coefficients by assignment.
  * When a variable is assigned, the difference is multiplied by a coefficient (2 by default). This is to account for the effect of the assignment on the frequency of use of the variable.
  * Calculation of VariableHardUsage:.
  * The VariableHardUsage is obtained by summing all these deviation values.

### Example

Suppose, for example, that there are three reference points in a function, each with line numbers 10, 20, and 30, and that some assignments are made and some are not made. In this case, the line number of the first occurrence is 10.

* Reference A: line 10, with assignment
* Reference B: line 20, no assignment
* Reference C: line 30, with assignment

In this case, VariableHardUsage is calculated as follows

* Reference A: (10 - 10) * 2 = 0
* Reference B: (20 - 10) * 1 = 10
* Reference C: (30 - 10) * 2 = 40

Summing these, VariableHardUsage is 0 + 10 + 40 = 50.

VariableHardUsage is thus calculated as a measure of the frequency of use and scope of a variable. This metric can be used to quantitatively evaluate the usage of local variables within a function and help improve code readability and maintainability.

