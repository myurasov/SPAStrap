/**
 * Autofill fix
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

app.directive('mymAutofillFix', function() {
  return function(scope, elem, attrs) {

    var intervalId = setInterval(function fix() {
      elem.find('input, textarea, select').each(function (i, e) {
        if (e.value) {
          $(e).trigger('change');
        }
      })
    }, 1000);

    scope.$on('$destroy', function() {
      clearInterval(intervalId);
    });
  };
});