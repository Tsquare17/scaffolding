<?php

namespace Tsquare\Scaffolding\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

class MakeSet extends Command
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

        $this->setDescription('Generate files using a set of templates config files.');

        parent::__construct('make:set');
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name');
        $this->addArgument('set', InputArgument::REQUIRED, 'Path to the template set');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $dir = $this->templatePath . '/' . $input->getArgument('set');
        if (!is_dir($dir)) {
            $output->writeln("<error>{$dir} does not exist.</>");

            return 0;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $template = FileTemplate::init($dir . '/' . $file);

            $template->name($input->getArgument('name'));

            if (!$template->getAppBasePath()) {
                $template->appBasePath(getcwd());
            }

            $generator = new FileGenerator($template);

            $write = $generator->create();

            if (!$write) {
                $output->writeln("<error>Failed to write to {$generator->getPathString()}</>");
            } else {
                $output->writeln("<info>Created {$generator->getPathString()}</>");
            }
        }

        return 0;
    }
}
