<?php

define('DOCMAN_VERSION', '1.0.2');

require __DIR__ . '/../vendor/autoload.php';

function tableEscape($text)
{
    return str_replace('|', '&#124;', $text);
}

if (!isset($argv[1])) {
    exit("Please specify a command\n");
}

if ($argv[1] == 'version') {
    exit("docman v" . DOCMAN_VERSION . "\n");
}

if ($argv[1] != 'generate') {
    exit("Command " . $argv[1] . " not found\n");
}

if (!isset($argv[2])) {
    exit("Please specify an output file\n");
}

$methodsFile = $argv[2];

if (strpos($methodsFile, '//') !== 0) {
    $methodsFile = getcwd() . '/' . $methodsFile;
}

$input = getcwd() . '/doc.yml';

use Symfony\Component\Yaml\Yaml;

$data = Yaml::parse(file_get_contents($input));

if (!isset($data['interface'])) {
    exit("Interface not found\n");
}

$definedErrorCodes = [];
if (isset($data['error_codes'])) {
    $definedErrorCodes = $data['error_codes'];
}

$output = '# Interface' . "\n\n";

$packages = [];

foreach ($data['interface'] as $name => $method) {
    $packageKey = isset($method['package']) ? strtolower($method['package']) : 'other';

    if (!isset($packages[$packageKey])) {
        $packages[$packageKey] = [];
    }

    $packages[$packageKey][$name] = $method;
}

ksort($packages);

foreach ($packages as $packageName => $package) {
    $output .= '## Package ' . ucwords(str_replace('-', ' ', $packageName)) . "\n\n";

    ksort($package);

    foreach ($package as $methodName => $method) {
        $output .= '### `' . $methodName . "`\n\n";

        if (isset($method['description'])) {
            $output .= $method['description'] . "\n\n";
        }

        if (isset($method['params'])) {
            $output .= 'Name | Type | Required | Default | Description' . "\n";
            $output .= '--- | --- | --- | --- | ---' . "\n";

            foreach ($method['params'] as $paramName => $param) {
                $required = $param['required'] ? 'Yes' : 'No';
                $description = isset($param['description']) ? $param['description'] : '';
                $default = isset($param['default']) ? $param['default'] : '';

                $output .= '`' . $paramName . '` | *' . tableEscape($param['type']) . '* | ' . $required . ' | ' . $default . ' | ' . tableEscape($description) . "\n";
            }

            $output .= "\n";
        }

        if (isset($method['error_codes'])) {
            $output .= '##### Error Codes' . "\n";

            $output .= 'Code | Description' . "\n";
            $output .= '--- | ---' . "\n";

            foreach ($method['error_codes'] as $code) {
                $output .= $code . ' | ' . (isset($definedErrorCodes[$code]) ? $definedErrorCodes[$code] : 'Unknown') . "\n";
            }

            $output .= "\n\n";
        }

        if (isset($method['examples'])) {
            foreach ($method['examples'] as $exampleName => $example) {
                $output .= '##### Example ' . ucfirst($exampleName) . "\n";

                if (isset($example['json'])) {
                    $output .= '```' . "\n";

                    $array = json_decode($example['json'], true);
                    $output .= json_encode($array, JSON_PRETTY_PRINT) . "\n";

                    $output .= '```' . "\n";
                }

                if (isset($example['text'])) {
                    $output .= '```' . "\n";

                    $output .= $example['text'] . "\n";

                    $output .= '```' . "\n";
                }
            }
        }
    }
}

file_put_contents($methodsFile, $output);
