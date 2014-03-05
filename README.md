SPA Bootstrap
=============

Bootstrap project for creating SPA sites with RESTful API backend. Utilizes __Angular__, __Bootstrap__, __Silex__, __Doctrine__, __Symforny Components__.

Installation
------------

* Run `composer update` in root dir
* Run `bower update` in `src/www/vendor`
* Edit database settings in `src/config.dev.php`
* Create database for your project
* Run `sudo src/cli/console app:service:install`
* Add users with `app:admin:users` command
* Build LESS files like this:

		$ mkdir src/www/build
		$ lessc src/www/dev/less/main.less > src/www/build/main.css
		$ lessc src/www/dev/less/bootstrap/bootstrap.less > src/www/build/bootstrap.css
		
* Done!

Requirements
------------

* __php__ 5.3.3+
* __composer__
* __bower__
* LESS compiler

Demo
----

http://spa-bootstrap.yurasov.me/

_test@example.com / password_


License
-------

Copyright (c) 2014 Mikhail Yurasov <me@yurasov.me>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.