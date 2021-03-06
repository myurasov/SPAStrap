/**
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

var app = angular.module('SPABootstrap', ['ui.router']);

// api endpoint
app.factory('apiEndpoint', function ($window) {
  var pathName = $window.location.pathname;

  // remove trailing slash
  if (pathName.substr(-1) === '/') {
    pathName = pathName.substr(0, pathName.length - 1);
  }

  return pathName + '/api/v1';
});

// get current user on startup
app.run(
  function ($rootScope, $http, $state, apiEndpoint) {
    $http.get(apiEndpoint + '/users/current')
      .then(function ok(e) {
        $rootScope.user = e.data;
      }, function err() {
        $state.go('login');
      });
  }
);

// routing
app.config(

  function ($urlRouterProvider, $stateProvider) {

    $urlRouterProvider.otherwise('/login');

    $stateProvider

      .state('app', {
        url: '/app',
        views: {
          contentView: {
            templateUrl: 'views/app.html',
          }
        }
      })

      .state('login', {
        url: '/login',
        views: {
          contentView: {
            templateUrl: 'views/login.html',
            controller: 'LoginController'
          }
        }
      })
  }
);
