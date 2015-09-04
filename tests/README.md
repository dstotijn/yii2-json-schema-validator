This directory contains various tests for yii2-json-schema-validator.

Tests in `codeception` directory are developed with [Codeception PHP Testing Framework](http://codeception.com/).

Follow these steps to prepare for the tests:

1. Install Codeception if it's not yet installed:

   ```
   composer global require "codeception/codeception=2.0.*"
   composer global require "codeception/specify=*"
   composer global require "codeception/verify=*"
   ```

   If you've never used Composer for global packages run `composer global status`. It should output:

   ```
   Changed current directory to <directory>
   ```

  Then add `<directory>/vendor/bin` to you `PATH` environment variable. Now we're able to use `codecept` from command
  line globally.

2. Build the test suites:

   ```
   codecept build
   ```

3. Now you can run the tests with the following commands:

   ```
   codecept run
   ```

Code coverage support
---------------------

You can run the tests and collect coverage with the following command:

```
codecept run --coverage-html --coverage-xml

You can see code coverage output under the `tests/_output` directory.
