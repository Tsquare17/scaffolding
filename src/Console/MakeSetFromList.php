<?php

namespace Tsquare\Scaffolding\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

class MakeSetFromList extends Command
{
    protected string $templatePath;

    /**
     * MakeSetFromList constructor.
     * @param $templatePath
     */
    public function __construct($templatePath)
    {
        $this->templatePath = $templatePath;

        $this->setDescription('Generate files using a list of templates config files.');

        parent::__construct('make:fromFiles');
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name');
        $this->addArgument('files', InputArgument::IS_ARRAY, 'Set of template files.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $files = $input->getArgument('files');

        foreach ($files as $file) {
            if (!file_exists($this->templatePath . '/' . $file)) {
                $output->writeLn("<error>{$file} does not exist.</>");

                return 0;
            }

            $template = FileTemplate::init($this->templatePath . '/' . $file);

            $template->name($input->getArgument('name'));

            if (!$template->getAppBasePath()) {
                $template->appBasePath(getcwd());
            }

            $generator = new FileGenerator($template);

            $write = $generator->create();

            if (!$write) {
                $output->writeln("<error>Failed to write to {$generator->getPathString()}</>");
                continue;
            }

            $output->writeln("<info>{$generator->getPathString()}</>");
        }

        return 0;
    }
}
