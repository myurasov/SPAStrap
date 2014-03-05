<?php

/**
 * Base entity class
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Entities;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class Entity
{
  /**
   * Validate entity
   *
   * @ORM\PreFlush
   *
   * @return bool
   * @throws ValidatorException
   */
  public function validate() {

    $validator = Validation::createValidatorBuilder()
      ->enableAnnotationMapping()
      ->getValidator();

    /* @var ConstraintViolationList $violations */
    $violations = $validator->validate($this);
    $message = array();

    if (count($violations) > 0) {

      /* @var $violation ConstraintViolation */
      foreach ($violations as $violation) {
        $message[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
      }

      throw new ValidatorException('Validation failed. ' . join(' ', $message));
    }

    return true;
  }
}