<?php

namespace Tsquare\Scaffolding\Console;

use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeFile extends BaseCommand
{
    /**
     * MakeController constructor.
     *
     * @param $templatePath
     * @param $hidden
     */
    public function __construct($templatePath, $hidden)
    {
        $this->setDescription('Generate a file using a template config file.');

        $this->hidden = $hidden;

        parent::__construct('make:file', $templatePath);
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name');
        $this->addArgument('template', InputArgument::REQUIRED, 'Name of the template');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $template = FileTemplate::init($this->templatePath . '/' . $input->getArgument('template') . '.php');

        foreach ($this->replacementTokens() as $token => $modifier) {
            $template->addReplacementToken($token, $modifier);
        }

        $this->inputName = (string) $input->getArgument('name');
        $this->validateName();

        $template->name($this->inputName);

        if (!$template->getAppBasePath()) {
            $template->appBasePath(getcwd());
        }

        $generator = new FileGenerator($template);

        $write = $generator->create();

        if (!$this->validateWriteSuccess($write, $generator->getPathString(), $output)) {
            return 0;
        }

        $output->writeln("<info>{$generator->getPathString()}</>");

        return 0;
    }
}
