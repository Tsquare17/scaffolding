<?php

namespace Tsquare\Scaffolding;

use Tsquare\Scaffolding\Console\MakeFile;
use Tsquare\Scaffolding\Console\MakeSet;
use Symfony\Component\Console\Application;
use Tsquare\Scaffolding\Console\MakFrom;

class App extends Application
{
    protected string $templatePath;

    protected bool $hideDefaultCommands = false;

    public function __construct($templatePath, $version = '1.0.0', $appName = 'Scaffolding')
    {
        parent::__construct($appName, $version);

        $this->templatePath = $templatePath;

        $this->addDefaultCommands();

        $this->addCustomCommands();
    }

    public function addDefaultCommands(): void
    {
        $this->add(new MakeFile($this->templatePath, $this->hideDefaultCommands));
        $this->add(new MakeSet($this->templatePath, $this->hideDefaultCommands));
        $this->add(new MakFrom($this->templatePath, $this->hideDefaultCommands));
    }

    public function addCustomCommands(): void
    {
    }
}
