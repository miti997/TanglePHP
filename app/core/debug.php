<?php

declare(strict_types=1);

/*
 * Copyright (c) 2023 Gozman-Pop Dumitru
 *
 * Licensed under the MIT License.
 */

function debug($variable = null)
{
    $trace = debug_backtrace();

    echo '<b>FILE: ' . $trace[0]['file'] . ' LINE: ' . $trace[0]['line'] . '</b><br>';
    echo "<pre>";
    print_r($variable);
    echo "</pre>";
}
