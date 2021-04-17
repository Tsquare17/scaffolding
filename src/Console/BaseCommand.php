<?php

namespace Tsquare\Scaffolding\Console;

use Symfony\Component\Console\Command\Command;

/**
 * Class BaseCommand
 * @package Tsquare\Scaffolding\Console
 */
class BaseCommand extends Command
{
    protected string $templatePath;
    protected string $inputName = '';
    protected bool $hidden = false;

    /**
     * BaseCommand constructor.
     * @param $command
     * @param $templatePath
     */
    public function __construct($command, $templatePath)
    {
        $this->templatePath = $templatePath;

        parent::__construct($command);
    }

    /**
     * Configure command.
     */
    public function configure(): void
    {
        $this->setHidden($this->hidden);
    }

    /**
     * Ensure the name is in the singular form.
     */
    protected function validateName(): void
    {
        if (strpos(strrev($this->inputName), 'sie') === 0) {
            $this->inputName = rtrim($this->inputName, 'ies') . 'y';
        } elseif (strpos(strrev($this->inputName), 's') === 0) {
            $this->inputName = rtrim($this->inputName, 's');
        }
    }

    /**
     * @param $success
     * @param $path
     * @param $output
     * @return bool
     */
    protected function validateWriteSuccess($success, $path, $output): bool
    {
        if (!$success) {
            if (file_exists($path)) {
                $output->writeln("<error>{$path} already exists.</>");

                return false;
            }

            $output->writeln("<error>Failed to write to {$path}</>");

            return false;
        }

        return true;
    }
}
