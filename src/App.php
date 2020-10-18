<?php

namespace Tsquare\Scaffolding;

use Tsquare\Scaffolding\Console\MakeClass;
use Tsquare\Scaffolding\Console\MakeSet;
use Symfony\Component\Console\Application;

class App extends Application
{
    public function __construct($version, $templatePath)
    {
        parent::__construct('Scaffolding', $version);

        $this->add(new MakeClass($templatePath));
        $this->add(new MakeSet($templatePath));
    }
}
