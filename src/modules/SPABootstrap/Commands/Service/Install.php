<?php

/**
 * Installation
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Commands\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Command
{
  protected function configure()
  {
    $this
      ->setName('app:service:install')
      ->setDescription('Install app')
      ->addOption('drop-database', null, InputOption::VALUE_NONE, 'Drop database');
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->executeCommand('app:service:create-paths', '--clean', $output);

    if ($input->getOption('drop-database')) {
      $this->executeCommand('orm:schema-tool:drop', '--force', $output);
      $this->executeCommand('orm:schema-tool:create', '', $output);
    } else {
      $this->executeCommand('orm:schema-tool:update', '--force', $output);
    }

    $this->executeCommand('orm:generate-proxies', '', $output);

    $this->executeCommand('mym:auth-service:install', '', $output);
  }

  private function executeCommand($name, $options = '', OutputInterface $output)
  {
    $commandLine = $name . (empty($options) ? '' : ' ' . $options);
    $output->writeln('<comment>Executing ' . $commandLine . '</comment>');

    $command = $this->getApplication()->find($name);
    $input = new StringInput($commandLine);

    $command->run($input, $output);
  }
}