<?php

require __DIR__ . '/../vendor/autoload.php';

if (!isset($argv[1])) {
    exit("Please specify a command\n");
}

if ($argv[1] != 'generate') {
    exit("Command " . $argv[1] . " not found\n");
}

if (!isset($argv[2])) {
    exit("Please specify a md file name\n");
}

