<?php

/**
 * User
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Entities;

use Doctrine\ORM\Mapping as ORM;
use mym\Auth\AbstractAuthService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(
 *  repositoryClass="SPABootstrap\Repositories\UserRepository"
 * )
 *
 * @Serializer\ExclusionPolicy("all")
 */
class User extends Entity
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   * @Serializer\Expose
   */
  protected $id;

  /**
   * @ORM\OneToOne(
   *  targetEntity="UserProfile",
   *  cascade={"persist","remove"}
   * )
   *
   * @Serializer\Expose
   * @var UserProfile
   */
  protected $profile;

  public function __construct()
  {
    $this->profile = new UserProfile();
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

  public function setProfile($profile)
  {
    $this->profile = $profile;
  }

  public function getProfile()
  {
    return $this->profile;
  }

  //</editor-fold>
}