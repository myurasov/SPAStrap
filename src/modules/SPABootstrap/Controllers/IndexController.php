<?php

namespace SPABootstrap\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
  /**
   * @var \Twig_Environment
   */
  protected $twig;

  public function indexAction(Request $request)
  {
    $response = new Response();
    $response->setPrivate();
    $response->setContent($this->twig->render('www/Index.twig', array()));


    return $response;
  }

  //<editor-fold desc="accessors">

  public function setTwig($twig)
  {
    $this->twig = $twig;
  }

  public function getTwig()
  {
    return $this->twig;
  }

  //</editor-fold>
}