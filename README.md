# Scaffolding

## CLI component using tsquare/file-generator to generate classes or sets of classes.

### Installation
`composer require tsquare/scaffolding`

### Usage:
* Create a template file e.g., ./template-config/Example.php
```php
<?php

use Tsquare\FileGenerator\FileTemplate;

/**
 * @var FileTemplate $template
 */


/**
 * Define the application root.
 */
$template->appDir(dirname(__DIR__, 1));


/**
 * Define the base path for the class.
 */
$template->path(dirname(__DIR__, 1) . '/Sample');


/**
 * Optionally, define the name of the file.
 */
$template->fileName('file');


/**
 * Optionally, define the name of the class. The name of the template file will be used if not specified.
 */
$template->className('Example');


/**
 * Optionally, define the class namespace. By default, the namespace will emulate the directory structure.
 */
$template->namespace('Sample\\Models\\ExampleModels\\ExampleModel');


/**
 * Optionally, define a rule for the class name. {class} is replaced by the class name.
 */
$template->nameRule('{name}Model');


/**
 * Optionally, define a rule for the path. {name} is replaced by the name specified on command (Example) or the defined className.
 * If usesClassNameRule is true, the name generated by classNameRule will be used instead.
 */
$template->pathRule('Models/{name}s/{name}', true);


/**
 * Set the contents of the class header.
 */
$template->header('
// Add imports, comments, etc.
');


/**
 * Set the contents of the class body.
 */
$template->body('
    public function insert{name}($data) {
        $this->{camel}Model->insert($data);
    }
');
```

* Alternatively, create non-class files, or classes using a single template block.
```php
<?php

use Tsquare\FileGenerator\FileTemplate;

/**
 * @var FileTemplate $template
 */


/**
 * Define the application root.
 */
$template->appDir(dirname(__DIR__, 1));


/**
 * Define the base path for the file.
 */
$template->path(dirname(__DIR__, 1) . '/Sample');


/**
 * Define the file name.
 */
$template->fileName('default');


/**
 * Define the contents of the file.
 */
$template->fileContent('
namespace App\Foo\{name};

$foo = {underscore}s;
$bar = {dash};
');
```

```php
#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Tsquare\Scaffolding\App;

$command = new App('1.0.0', __DIR__ . '/template-config');
$command->run();
```
