/**
 * Utils
 * @copyright 2014 Mikhail Yurasov <me@yurasov.me>
 */

app.value('utils', {

  // 0..5
  passwordStrength: function (password) {
    var score = 1;

    if (password.length < 1)
      return 0;

    if (password.length < 4)
      return 0;

    if (password.length >= 8)
      score++;
    if (password.length >= 10)
      score++;
    if (password.match(/\d+/))
      score++;
    if (password.match(/[a-z]/) &&
      password.match(/[A-Z]/))
      score++;
    if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,\-,£,(,)]/))
      score++;

    return score;
  }
});