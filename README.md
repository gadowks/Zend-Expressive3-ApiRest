# Zend-Expressive3-ApiRest
Simple Example Api Rest Middleware with Zend-Expressive 3

## Install with Composer

```
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install or composer install
```
## Getting with Curl

```
    $ curl -H 'content-type: application/json' -v -X GET http://127.0.0.1:8080/api/books
    $ curl -H 'content-type: application/json' -v -X GET http://127.0.0.1:8080/api/books/:id
    $ curl -H 'content-type: application/json' -v -X POST -d '{"title":"foo_bar","price":"19.99"}' http://127.0.0.1:8080/api/books
    $ curl -H 'content-type: application/json' -v -X PUT -d '{"title":"foo_bar","price":"19.99"}' http://127.0.0.1:8080/api/books/:id
    $ curl -H 'content-type: application/json' -v -X DELETE http://127.0.0.1:8080/api/books/:id
```
