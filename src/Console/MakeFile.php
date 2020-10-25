<?php

namespace Tsquare\Scaffolding\Console;

use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeFile extends Command
{
    protected string $templatePath;

    /**
     * MakeController constructor.
     *
     * @param $templatePath
     */
    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;

        $this->setDescription('Generate a file using a template config file.');

        parent::__construct('make:file');
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

        $template->name($input->getArgument('name'));

        if (!$template->getAppBasePath()) {
            $template->appBasePath(getcwd());
        }

        $generator = new FileGenerator($template);

        $write = $generator->create();

        if (!$write) {
            $output->writeln("<error>Failed to write to {$generator->getPathString()}</>");
            return 0;
        }

        $message = "<info>Created {$generator->getPathString()}</>";
        if (is_file($generator->getPathString())) {
            $message = "<info>Edited {$generator->getPathString()}</>";
        }

        $output->writeln($message);

        return 0;
    }
}
