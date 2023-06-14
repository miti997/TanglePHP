<?php

/*
 * Copyright (c) 2023 Gozman-Pop Dumitru
 *
 * Licensed under the MIT License.
 */

use app\App;

define('DS', DIRECTORY_SEPARATOR);
define('SRC', ROOT . 'app' . DS . 'src' . DS);
define('CORE', ROOT . 'app' . DS . 'core' . DS);
define('BUILT_COMPONENTS', SRC . 'built_templates' .  DS);
define('TEMPLATES', SRC . 'templates' . DS);
define('COMPONENTS', SRC . 'components' . DS);

$config = require_once CORE . 'config.php';

require_once CORE . 'autoloader.php';
require_once CORE . 'debug.php';

define('DEV', $config['dev_mode']);

$handler = new App();
$handler->start();
