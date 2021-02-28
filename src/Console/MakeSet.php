<?php

namespace Tsquare\Scaffolding\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsquare\FileGenerator\FileGenerator;
use Tsquare\FileGenerator\FileTemplate;

class MakeSet extends BaseCommand
{
    /**
     * MakeController constructor.
     *
     * @param $templatePath
     * @param $hidden
     */
    public function __construct($templatePath, $hidden)
    {
        $this->setDescription('Generate files using a set of templates config files.');

        $this->hidden = $hidden;

        parent::__construct('make:set', $templatePath);
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name');
        $this->addArgument('set', InputArgument::REQUIRED, 'Path to the template set');

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
        $dir = $this->templatePath . '/' . $input->getArgument('set');
        if (!is_dir($dir)) {
            $output->writeln("<error>{$dir} does not exist.</>");

            return 0;
        }

        $this->inputName = (string) $input->getArgument('name');
        $this->validateName();

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $template = FileTemplate::init($dir . '/' . $file);

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
