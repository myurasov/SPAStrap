<?php

/**
 * Users API controller
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

namespace SPABootstrap\Controllers\API;

use SPABootstrap\Entities\User;
use mym\REST\ORM\RESTController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UsersController extends RESTController
{
  /**
   * POST
   *
   *  (post) email
   *  (post) password
   *
   * @param Request $request
   * @return \mym\REST\SerializedResponse
   * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   */
  public function loginAction(Request $request)
  {
    // check params
    if (!$request->request->has('email') || !$request->request->has('password')) {
      throw new BadRequestHttpException('Wrong parameters');
    }

    // load user

    $result = $this->em
      ->createQuery('SELECT u FROM app:User u, app:UserProfile p WHERE u.profile = p AND p.email = ?1')
      ->setParameter(1, $request->request->get('email'))
      ->getResult();

    /** @var User $user */
    $user = isset($result[0]) ? $result[0] : null;

    // check password
    if (!$user || !$user->getProfile()->verifyPassword($request->request->get('password'))) {
      throw new AccessDeniedHttpException('Wrong email/password');
    }

    // create token
    $token = $this->authService->createToken($user->getId());

    // set cookie
    $this->authService->saveTokenToCookie($this->response, $token);

    //

    $this->response->setData(array(
        'authToken' => $token,
        'expires' => $this->authService->getExpiration($token),
        'user' => $user
      ));

    return $this->response;
  }

  /**
   * Logout
   *
   * POST
   *    (cookie/get/post) <authTokenName>
   *
   * @param Request $request
   * @return \mym\REST\SerializedResponse
   */
  public function logoutAction(Request $request)
  {
    $token = $this->authService->getTokenFromRequest($request);

    if ($token) {
      // remove token
      $this->authService->removeToken($token);

      // clear in cookie
      $this->authService->clearTokenCookie($this->response);
    }

    return $this->response;
  }

  public function getResourceAction(Request $request)
  {
    /** @var User $user */
    $user = $this->loadUser($request, true /* required */);

    if ($request->attributes->get('id') === 'current'
      || $user->getId() == $request->attributes->get('id')) {
        $this->response->setData($user);
    } else {
      throw new AccessDeniedHttpException();
    }

    return $this->response;
  }

  //<editor-fold desc="accessors">

  //</editor-fold>
}