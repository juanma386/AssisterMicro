# AssisterMicro
[![Patreon](https://img.shields.io/badge/Support-Patreon-orange.svg?style=flat&logo=patreon)](https://www.patreon.com/hexomecloud)
[![PHPUnit](https://img.shields.io/github/actions/workflow/status/juanma386/AssisterMicro/.github/workflows/phpunit.yml?branch=main&label=PHPUnit&logo=php&style=flat-square
)](https://github.com/juanma386/AssisterMicro/actions)

#### Community
[![Follow me on GitHub](https://img.shields.io/github/followers/juanma386?style=social)](https://github.com/juanma386)
[![Facebook](https://img.shields.io/badge/Facebook-Like-blue?style=social&logo=facebook)](https://www.facebook.com/hexome.cloud/)
[![Instagram](https://img.shields.io/badge/Instagram-Follow-blue?style=social&logo=instagram)](https://www.instagram.com/hexome.cloud/)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-blue?style=social&logo=linkedin)](https://www.linkedin.com/company/hexome-cloud/)

### AssisterMicro Framework
AssisterMicro is a small and lightweight PHP framework designed for building microservices. It aims to provide assistance and streamlined functionality for developing microservices efficiently.

Here are step-by-step instructions in English to install the AssisterMicro framework using Composer:

### 1. Open a Terminal or Command Line Interface
Access your command-line interface or terminal application.

### 2. Run the `composer require` Command
Execute the following command to add the AssisterMicro framework to your PHP project. Make sure you are in the root directory of your project when running this command.

```bash
composer require juanma386/AssisterMicro
```

### 3. Wait for Composer to Install Dependencies
Composer will start searching for the AssisterMicro framework in the default repository and download it along with its required dependencies. Wait until the installation process completes.

### 4. Verify the Installation
Once Composer finishes installing the framework, you can confirm if the AssisterMicro framework has been successfully added to your project. Check the `composer.json` file in your project directory to ensure that the dependency has been included.

### 5. Utilize the Installed Framework
Now that the AssisterMicro framework has been installed, you can begin using its features and functionalities in your PHP project. Refer to the framework's documentation or resources to understand how to utilize its features within your code.

These steps will guide you through the process of installing the AssisterMicro framework via Composer and incorporating it into your PHP project.

The command you provided seems to run PHPUnit tests for the AssisterMicro framework with code coverage enabled. Here's a breakdown of the command:

```bash
php8.3 vendor/phpunit/phpunit/phpunit vendor/AssisterMicro/*/ --coverage-text -v
```

Explanation of the command:

- `php8.3`: Specifies the PHP version (PHP 8.3 in this case) to be used to execute the PHPUnit tests.
- `vendor/phpunit/phpunit/phpunit`: Points to the PHPUnit binary in the vendor directory.
- `vendor/AssisterMicro/*`: Refers to the path where the tests for the AssisterMicro framework are located.
- `--coverage-text`: Generates a text-based code coverage report after running the tests.
- `-v`: Enables verbose mode, providing more detailed output during test execution.

This command is designed to execute PHPUnit tests for the AssisterMicro framework using PHP version 8.3 and output a text-based coverage report.

Please note that the success of this command depends on the presence of PHPUnit tests within the AssisterMicro framework and the proper configuration of PHPUnit for that specific framework.
