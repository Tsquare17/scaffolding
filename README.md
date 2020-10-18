# Scaffolding

## CLI component using tsquare/class-generator to generate classes or sets of classes.

### Installation
`composer require tsquare/scaffolding`

### Usage:
```
#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Tsquare\Scaffolding\App;

$command = new App('1.0.0', __DIR__ . '/stubs');
$command->run();
```