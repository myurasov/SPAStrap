<?php

/**
 * User profile
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Entities;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @Serializer\ExclusionPolicy("all")
 */
class UserProfile extends Entity
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   */
  protected $id;

  /**
   * @ORM\Column(type="string")
   * @Serializer\Expose
   */
  protected $firstName;

  /**
   * @ORM\Column(type="string")
   * @Serializer\Expose
   */
  protected $lastName;

  /**
   * @ORM\Column(type="string", unique=true)
   * @Serializer\Expose
   * @Assert\Email
   * @Assert\Length(min=1,max=256)
   */
  protected $email;

  /**
   * @ORM\Column(type="string")
   */
  protected $passwordHash;

  public function setEmail($email) {
    $this->email = strtolower($email);
  }

  protected function hashPassword($password)
  {
    return password_hash($password, PASSWORD_BCRYPT);
  }

  public function verifyPassword($password)
  {
    return password_verify($password, $this->passwordHash);
  }

  public function setPassword($password)
  {
    // check password length
    if (strlen($password) < 5) {
      throw new \Exception('Password should 5 or more characters');
    }

    $this->passwordHash = $this->hashPassword($password);
  }

  //<editor-fold desc="accessors">

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;
  }

  public function getFirstName()
  {
    return $this->firstName;
  }

  public function setLastName($lastName)
  {
    $this->lastName = $lastName;
  }

  public function getLastName()
  {
    return $this->lastName;
  }

  public function setPasswordHash($passwordHash)
  {
    $this->passwordHash = $passwordHash;
  }

  public function getPasswordHash()
  {
    return $this->passwordHash;
  }

  public function getEmail()
  {
    return $this->email;
  }

  //</editor-fold>
}