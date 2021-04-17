<?php

namespace Tsquare\Scaffolding;

use Tsquare\Scaffolding\Console\MakeFile;
use Tsquare\Scaffolding\Console\MakeSet;
use Symfony\Component\Console\Application;
use Tsquare\Scaffolding\Console\MakFrom;

/**
 * Class App
 * @package Tsquare\Scaffolding
 */
class App extends Application
{
    protected string $templatePath;

    protected bool $hideDefaultCommands = false;

    /**
     * App constructor.
     * @param $templatePath
     * @param string $version
     * @param string $appName
     */
    public function __construct($templatePath, $version = '1.0.0', $appName = 'Scaffolding')
    {
        parent::__construct($appName, $version);

        $this->templatePath = $templatePath;

        $this->addDefaultCommands();

        $this->addCustomCommands();
    }

    /**
     * Add default commands.
     */
    public function addDefaultCommands(): void
    {
        $this->add(new MakeFile($this->templatePath, $this->hideDefaultCommands));
        $this->add(new MakeSet($this->templatePath, $this->hideDefaultCommands));
        $this->add(new MakFrom($this->templatePath, $this->hideDefaultCommands));
    }

    /**
     * Add custom commands.
     */
    public function addCustomCommands(): void
    {
    }
}
