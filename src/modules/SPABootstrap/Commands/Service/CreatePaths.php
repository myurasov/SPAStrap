<?php

/**
 * Create paths
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Commands\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePaths extends Command
{
  protected function configure()
  {
    $this
      ->setName('app:service:create-paths')
      ->setDescription('Create app paths')
      ->addOption('clean', 'c', InputOption::VALUE_NONE, 'Cleans temporary data');
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $app = $this->getHelper('silexApp')->getApp();

    if ($input->getOption('clean')) {
      // clear temporary data
      @system(sprintf('rm -rf %s/*', escapeshellarg($app['paths.temp'])));
      $output->writeln('Temporary data cleaned');
    }

    // create paths
    @mkdir($app['paths.data'], 0777, true);
    @mkdir($app['paths.temp'], 0777, true);
    @mkdir($app['paths.logs'], 0777, true);
    @mkdir($app['orm.proxies_dir'], 0777, true);
    @mkdir($app['serialized_response.cache_dir'], 0777, true);
    @mkdir($app['twig.options']['cache'], 0777, true);

    // set rights
    @system(sprintf('chmod -R 0777 %s', escapeshellarg($app['paths.logs'])));
    @system(sprintf('chmod -R 0777 %s', escapeshellarg($app['paths.temp'])));

    //

    $output->writeln('Paths created');
  }
}