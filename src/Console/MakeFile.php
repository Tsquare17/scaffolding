<?php

namespace Tsquare\Scaffolding\Console;

use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeFile extends BaseCommand
{
    /**
     * MakeController constructor.
     *
     * @param $templatePath
     */
    public function __construct($templatePath)
    {
        $this->setDescription('Generate a file using a template config file.');

        parent::__construct('make:file', $templatePath);
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name');
        $this->addArgument('template', InputArgument::REQUIRED, 'Name of the template');
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
