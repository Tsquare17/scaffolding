<?php

namespace Tsquare\Scaffolding\Console;

use Tsquare\ClassGenerator\ClassGenerator;
use Tsquare\ClassGenerator\ClassTemplate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MakeClass extends Command
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

        $this->setDescription('Generate a class file.');

        parent::__construct('make:class');
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the class.');
        $this->addArgument('template', InputArgument::REQUIRED, 'Name of the template.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $template = ClassTemplate::init($this->templatePath . '/' . $input->getArgument('template') . '.php');

        $template->name($input->getArgument('name'));

        $generator = new ClassGenerator($template);

        $write = $generator->create();

        if (!$write) {
            $output->writeln("<error>Failed to write to {$generator->getClassPathString()}</>");
            return 0;
        }

        $output->writeln("<info>Created class {$generator->getClassPathString()}</>");

        return 0;
    }
}