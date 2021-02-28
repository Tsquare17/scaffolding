<?php

namespace Tsquare\Scaffolding\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

class MakFrom extends BaseCommand
{
    /**
     * MakeSetFromList constructor.
     * @param $templatePath
     */
    public function __construct($templatePath)
    {
        $this->setDescription('Generate files using a list of templates config files.');

        parent::__construct('make:from', $templatePath);
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

        if (empty($files)) {
            $output->writeLn("<error>No files arguments supplied.</>");

            return 0;
        }

        $this->inputName = (string) $input->getArgument('name');
        $this->validateName();

        foreach ($files as $file) {
            if (!file_exists($this->templatePath . '/' . $file)) {
                $output->writeLn("<error>{$file} does not exist.</>");

                return 0;
            }

            $template = FileTemplate::init($this->templatePath . '/' . $file);

            $template->name($this->inputName);

            if (!$template->getAppBasePath()) {
                $template->appBasePath(getcwd());
            }

            $generator = new FileGenerator($template);

            $write = $generator->create();

            if (!$this->validateWriteSuccess($write, $generator->getPathString(), $output)) {
                continue;
            }

            $output->writeln("<info>{$generator->getPathString()}</>");
        }

        return 0;
    }
}
