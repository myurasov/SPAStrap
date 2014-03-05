<?php

/**
 * Manage users
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Commands\Admin;

use Doctrine\ORM\EntityManager;
use SPABootstrap\Entities\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Users extends Command
{
  protected function configure()
  {
    $this
      ->setName('app:admin:users')
      ->setDescription('Manage users')
      ->addOption('list', 'l', InputOption::VALUE_NONE, 'List users')
      ->addOption('create', 'c', InputOption::VALUE_NONE, 'Create user')
      ->addOption('edit', 'e', InputOption::VALUE_NONE, 'Modify user')
      ->addOption('delete', 'd', InputOption::VALUE_NONE, 'Delete user')
      ->addOption('delete-all', null, InputOption::VALUE_NONE, 'Delete all users')
      ->addArgument('id', InputArgument::OPTIONAL, 'ID of the user to modify');
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    if ($input->getOption('create')) {
      $this->createUser($input, $output);
    } else if ($input->getOption('list')) {
      $this->listUsers($input, $output);
    } else if ($input->getOption('delete')) {
      $this->deleteUser($input, $output);
    } else if ($input->getOption('delete-all')) {
      $this->deleteAllUsers($input, $output);
    } else if ($input->getOption('edit')) {
      $this->editUser($input, $output);
    }
  }

  private function deleteAllUsers(InputInterface $input, OutputInterface $output)
  {
    /** @var EntityManager $em */
    $em = $this->getHelperSet()->get('em')->getEntityManager();

    $em->createQuery('DELETE FROM app:User')->execute();
    $em->createQuery('DELETE FROM app:UserProfile')->execute();
  }

  private function deleteUser(InputInterface $input, OutputInterface $output)
  {
    $id = $input->getArgument('id');

    /** @var EntityManager $em */
    $em = $this->getHelperSet()->get('em')->getEntityManager();

    /** @var User $user */
    $user = $em->getRepository('app:User')->find($id);

    if (!$user) {
      throw new \RuntimeException('User not found');
    }

    $em->remove($user);
    $em->flush();
  }

  private function editUser(InputInterface $input, OutputInterface $output)
  {
    $id = $input->getArgument('id');

    /** @var EntityManager $em */
    $em = $this->getHelperSet()->get('em')->getEntityManager();

    /** @var User $user */
    $user = $em->getRepository('app:User')->find($id);

    if (!$user) {
      throw new \RuntimeException('User not found');
    }

    $this->createUser($input, $output, $user);
  }

  private function listUsers(InputInterface $input, OutputInterface $output)
  {
    /** @var EntityManager $em */
    $em = $this->getHelperSet()->get('em')->getEntityManager();

    /** @var TableHelper $table */
    $table = $this->getHelperSet()->get('table');
    $table->setHeaders(array('id', 'First', 'Last', 'Email'));

    /** @var User $user */
    foreach ($em->getRepository('app:User')->findAll() as $user) {
      $table->addRow(array(
          $user->getId(),
          $user->getProfile()->getFirstName(),
          $user->getProfile()->getLastName(),
          $user->getProfile()->getEmail()
        ));

    }

    $table->render($output);
  }

  private function createUser(InputInterface $input, OutputInterface $output, User $user = null)
  {
    /** @var DialogHelper $dialog */
    $dialog = $this->getHelperSet()->get('dialog');

    $firstName = $dialog->ask(
      $output,
      '<question>First Name:</question> ',
      $user ? $user->getProfile()->getFirstName() : null,
      array($user ? $user->getProfile()->getFirstName() : null)
    );

    $lastName = $dialog->ask(
      $output,
      '<question>Last Name:</question> ',
      $user ? $user->getProfile()->getLastName() : null,
      array($user ? $user->getProfile()->getLastName() : null)
    );

    $email = $dialog->ask(
      $output,
      '<question>Email:</question> ',
      $user ? $user->getProfile()->getEmail() : null,
      array($user ? $user->getProfile()->getEmail() : null)
    );

    $password = $dialog->askHiddenResponse(
      $output,
      '<question>Password:</question> '
    );

    $passwordConfirmation = $dialog->askHiddenResponseAndValidate(
      $output,
      '<question>Confirm password:</question> ',
      function ($value) use ($password) {

        if ($password !== $value) {
          throw new \RuntimeException("Password confirmation doesn't match");
        }

        return $value;
      },
      3
    );

    // save

    /** @var EntityManager $em */
    $em = $this->getHelperSet()->get('em')->getEntityManager();

    if (!$user) {
      $user = new User();
      $em->persist($user);
    }

    $user->getProfile()->setFirstName($firstName);
    $user->getProfile()->setLastName($lastName);
    $user->getProfile()->setEmail($email);
    $user->getProfile()->setPassword($password);

    $em->flush();
  }
}