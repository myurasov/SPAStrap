/**
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

app.controller('LayoutController',

  function ($scope, $rootScope, $location, $state, $http, apiEndpoint) {

    // populate vars
    $scope.host = $location.$$host;

    $scope.logout = function () {

      // clear auth token
      $http.post(apiEndpoint + '/users/logout.action')
        .then(function ok(e) {
          // remove user
          $rootScope.user = null;
          // redirect to login
          $state.go('login');
        });

    }
  }
);