/**
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

app.filter('ucfirst', function() {
  return function(input) {
    return input ? input.charAt(0).toUpperCase() + input.slice(1) : input;
  };
});