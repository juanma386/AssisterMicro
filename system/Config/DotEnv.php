<?php

/**
 * This file is part of AssisterMicro 1 framework.
 *
 * (c) CodeIgniter Foundation <support@hexome.cloud>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace AssisterMicro\Config;

use InvalidArgumentException;

/**
 * Environment-specific configuration
 */
class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;

    /**
     * Builds the path to our file.
     */
    public function __construct(string $path, string $file = '.env')
    {
        $this->setPath($path,$file);
    }

    private function getDirectory(): ?string {
        return $this->directory;
    }
    private function getFile() : ?string {
        return $this->file;
    }

    public function setPath(?string $path,?string $file ) : void {
        $this->setFile($file);
        $this->setDirectory($path);
        $this->inicializatePath();
    }

    private function inicializatePath() : void {
        $this->path = $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getFile();
    }

    private function setDirectory(?string $directory ) : void {
        $this->directory = rtrim($directory, DIRECTORY_SEPARATOR);
    }

    private function setFile( ?string $file ) : void {
        $this->file = $file;
    }

    /**
     * The main entry point, will load the .env file and process it
     * so that we end up with all settings in the PHP environment vars
     * (i.e. getenv(), $_ENV, and $_SERVER)
     */
    public function load(): bool
    {
        $vars = $this->parse();

        return $vars !== null;
    }

    /**
     * Parse the .env file into an array of key => value
     */
    public function parse(): ?array
    {
        // Check if the .env file exists, return null if not
        if (!is_file($this->path)) {
            return null;
        }

        // Ensure the file is readable, throw an exception if it's not
        if (!is_readable($this->path)) {
            throw new InvalidArgumentException("The .env file is not readable: {$this->path}");
        }

        $vars = []; // Initialize an empty array to store variables

        // Read the lines from the .env file, ignoring empty lines
        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments (lines starting with '#')
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // If the line contains an equal sign, it's a variable assignment
            if (strpos($line, '=') !== false) {
                // Normalize the variable and extract its name and value
                [$name, $value] = $this->normaliseVariable($line);

                // Store the variable in the $vars array
                $vars[$name] = $value;

                // Set the variable in the environment
                $this->setVariable($name, $value);
            }
        }

        // Return the array of parsed variables
        return $vars;
    }

    /**
     * Sets the variable into the environment. Will parse the string
     * first to look for {name}={value} pattern, ensure that nested
     * variables are handled, and strip it of single and double quotes.
     */
    protected function setVariable(string $name, string $value = '')
    {
        if (! getenv($name, true)) {
            putenv("{$name}={$value}");
        }

        if (empty($_ENV[$name])) {
            $_ENV[$name] = $value;
        }

        if (empty($_SERVER[$name])) {
            $_SERVER[$name] = $value;
        }
    }

    /**
     * Parses for assignment, cleans the $name and $value, and ensures
     * that nested variables are handled.
     */
    public function normaliseVariable(string $name, string $value = ''): array
    {
        // Split our compound string into its parts if there's an equal sign
        if (strpos($name, '=') !== false) {
            // Explode the string into $name and $value
            [$name, $value] = explode('=', $name, 2);
        }

        // Trim leading and trailing whitespace from $name and $value
        $name  = trim($name);
        $value = trim($value);

        // Sanitize the name by removing 'export' keyword and quotes
        $name = preg_replace('/^export[ \t]++(\S+)/', '$1', $name);
        $name = str_replace(['\'', '"'], '', $name);

        // Sanitize the value by handling quotes and nested variables
        $value = $this->sanitizeValue($value);
        $value = $this->resolveNestedVariables($value);

        // Return the sanitized $name and $value as an array
        return [$name, $value];
    }

    /**
     * Strips quotes from the environment variable value.
     *
     * This was borrowed from the excellent phpdotenv with very few changes.
     * https://github.com/vlucas/phpdotenv
     *
     * @throws InvalidArgumentException
     */
    protected function sanitizeValue(string $value): string
    {
            // If the value is empty, return it as is
            if (! $value) {
                return $value;
            }

            // Check if the value starts with a quote
            if (strpbrk($value[0], '"\'') !== false) {
                // Get the starting quote character
                $quote = $value[0];

                // Define the regular expression pattern to match quoted strings
                $regexPattern = sprintf(
                    '/^
                    %1$s          # Match a quote at the start of the value
                    (             # Capturing sub-pattern used
                    (?:          # We do not need to capture this
                    [^%1$s\\\\] # Any character other than a quote or backslash
                    |\\\\\\\\   # Or two backslashes together
                    |\\\\%1$s   # Or an escaped quote e.g \"
                    )*           # As many characters that match the previous rules
                    )             # End of the capturing sub-pattern
                    %1$s          # And the closing quote
                    .*$           # And discard any string after the closing quote
                    /mx',
                    $quote
                );

                // Extract the quoted value and handle escape sequences
                $value = preg_replace($regexPattern, '$1', $value);
                $value = str_replace("\\{$quote}", $quote, $value);
                $value = str_replace('\\\\', '\\', $value);
            } else {
                // Split the value at the first '#'
                $parts = explode(' #', $value, 2);
                // Take the trimmed value before '#'
                $value = trim($parts[0]);

                // Unquoted values cannot contain whitespace
                if (preg_match('/\s+/', $value) > 0) {
                    // Throw an exception for unquoted values with spaces
                    throw new InvalidArgumentException('.env values containing spaces must be surrounded by quotes.');
                }
            }

            // Return the processed value
            return $value;

    }

    /**
     *  Resolve the nested variables.
     *
     * Look for ${varname} patterns in the variable value and replace with an existing
     * environment variable.
     *
     * This was borrowed from the excellent phpdotenv with very few changes.
     * https://github.com/vlucas/phpdotenv
     */
    protected function resolveNestedVariables(string $value): string
    {
            // Check if the value contains '${}' patterns
            if (strpos($value, '$') !== false) {
                // Replace ${} patterns with corresponding values
                $value = preg_replace_callback(
                    '/\${([a-zA-Z0-9_\.]+)}/',
                    function ($matchedPatterns) {
                        $nestedVariable = $this->getVariable($matchedPatterns[1]);

                        // If the nested variable doesn't exist, return the original pattern
                        if ($nestedVariable === null) {
                            return $matchedPatterns[0];
                        }

                        // Return the value of the nested variable
                        return $nestedVariable;
                    },
                    $value
                );
            }

            // Return the processed value
            return $value;
    }

    /**
     * Search the different places for environment variables and return first value found.
     *
     * This was borrowed from the excellent phpdotenv with very few changes.
     * https://github.com/vlucas/phpdotenv
     *
     * @return string|null
     */
    protected function getVariable(?string $name) : ?string
    {
        $value = false;

        $getenv = getenv($name);

        // Check if the variable exists in $_ENV and set $value accordingly
        if (array_key_exists($name, $_ENV)) {
            $value = $_ENV[$name];
        }

        // Check if the variable exists in $_SERVER and update $value if necessary
        if (!$value && array_key_exists($name, $_SERVER)) {
            $value = $_SERVER[$name];
        }

        // If $value is still false, try retrieving it from getenv
        if (!$value) {
            $value = $getenv;
        }

        // If $value is still false, return null; otherwise, return the value
        return $value !== false ? $value : null;
    }
}
