/**
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

app.controller('LoginController',

  function ($scope, $rootScope, $http, $state, apiEndpoint) {

    $scope.profile = {};

    if ($rootScope.user) {
      $state.go('app');
    }

    $scope.login = function () {

      $scope.working = true;

      $http.post(apiEndpoint + '/users/login.action', $scope.profile)
        .then(function ok(e) {
          // set user
          $rootScope.user = e.data.user;
          // redirect to app
          $state.go('app');
        }, function err(e) {
          $scope.errorMessage = e.data.message;
        })
        .finally(function () {
          $scope.working = false;
        });
    }
  }
);