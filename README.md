# PHP CLI Tool for Analyzing Local Variable Usage

## Overview
This PHP CLI tool analyzes the usage of local variables in PHP source code, focusing on their scope and update frequency. It helps developers identify potential issues in handling local variables, improving code quality and maintainability.

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


