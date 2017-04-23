<?php

require __DIR__ . '/../vendor/autoload.php';

if (!isset($argv[1])) {
    exit("Please specify a command\n");
}

if ($argv[1] != 'generate') {
    exit("Command " . $argv[1] . " not found\n");
}

$input = getcwd() . '/doc.yml';
$methodsFile = getcwd() . '/METHODS.md';

use Symfony\Component\Yaml\Yaml;

$data = Yaml::parse(file_get_contents($input));

if (!isset($data['interface'])) {
    exit("Interface not found\n");
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
            $output .= 'Name | Required | Type | Description' . "\n";
            $output .= '--- | --- | --- | ---' . "\n";

            foreach ($method['params'] as $paramName => $param) {
                $required = $param['required'] ? 'Yes' : 'No';
                $description = isset($param['description']) ? $param['description'] : '';

                $output .= '`' . $paramName . '` | *' . $param['type'] . '* | ' . $required . ' | ' . $description . "\n";
            }

            $output .= "\n";
        }
    }
}

file_put_contents($methodsFile, $output);
