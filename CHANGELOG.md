# CHANGELOG

## v0.0.3 (2025-03-20)

### Features

 * The base of deviation of local variable abuse has been changed from the average number of rows to the first number of rows.
 * AssignOp has been added to the assignment decision in addition to Assign.
 * Add filename to report json.

## v0.0.2 (2025-03-19)

### Features

 * When determining the local variable severity, the coefficients are now taken into account when the variable is an assignment.

### Bug fix

 * Refactor remove interface Scope

## v0.0.1 (2025-03-18)

### Features

 * Add support for anonymous function. by @hirokinoue
 * Add namespace to scope output.

### Bug fix

 * Refactor how to find nodes. by @hirokinoue

