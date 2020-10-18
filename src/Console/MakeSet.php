<?php


namespace Tsquare\Scaffolding\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsquare\ClassGenerator\ClassGenerator;
use Tsquare\ClassGenerator\ClassTemplate;

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

        $this->setDescription('Generate class files using a set of templates.');

        parent::__construct('make:set');
    }

    /**
     * Set arguments.
     */
    public function configure(): void
    {
        $this->addArgument('set', InputArgument::REQUIRED, 'Path to the template set.');
        $this->addArgument('name', InputArgument::REQUIRED, 'Class name.');
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

            $template = ClassTemplate::init($dir . '/' . $file);

            $template->name($input->getArgument('name'));

            $generator = new ClassGenerator($template);

            $write = $generator->create();

            if (!$write) {
                $output->writeln("<error>Failed to write to {$generator->getClassPathString()}</>");
            } else {
                $output->writeln("<info>Created class {$generator->getClassPathString()}</>");
            }
        }

        return 0;
    }
}