# Scaffolding

## CLI component using tsquare/file-generator to generate classes or sets of classes.

### Installation
`composer require tsquare/scaffolding`

### Usage:

##### Create a template file e.g., ./template-config/Example.php
```php
<?php

use Tsquare\FileGenerator\FileTemplate;
use Tsquare\FileGenerator\FileEditor;

/**
 * @var FileTemplate $template
 */


/**
 * Define the application root.
 */
$template->appBasePath(dirname(__DIR__, 1));


/**
 * Define the base path for the file.
 */
$template->destinationPath(dirname(__DIR__, 1) . '/Sample');


/**
 * Define the file name. If not defined, the name specified in the command will be used.
 */
$template->fileName('{name}Extension');


/**
 * Define the contents of the file.
 */
$template->fileContent(<<<'FILE'
namespace App\Foo\{name};

$foo = '{underscore}s';
$bar = '{dash}';

function foo{name}() {
    return true;
}

FILE);


/*
 * Editing actions can be added, that will be used if the file already exists.
 */
$editor = new FileEditor();

$editor->insertBefore('
function inserted{pascal}Function() {
    return true;
}

', 'function foo{name}()');

$editor->insertAfter('
function another{pascal}Function()  {
    return true;
}
', 'function foo{name}() {
        return true;
    }
');

$editor->replace('another{pascal}Function', 'someOther{pascal}Function');

$template->ifFileExists($editor);
```

##### Create a file in the root of your application, e.g., scaffolding.php
```php
#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Tsquare\Scaffolding\App;

$command = new App('1.0.0', __DIR__ . '/template-config');
$command->run();
```

##### The following command will make a file supplying the name SampleFile, using the template Example.php

    php scaffolding.php make:file SampleFile Example


##### The following command will create a set of files, using the templates in the files directory, within template-config/.

    php scaffolding.php make:set Sample files


##### The following template replace tokens are available.
```
{name}        : ExampleName (the first argument provided)
{camel}       : exampleName
{pascal}      : ExampleName
{underscore}  : example_name
{dash}        : example-name
{name:plural} : ExampleNames
```
