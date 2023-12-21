# User
Lib called to reduce hassle in projects with jwt. Expected to be convenient solution for the purpose.

## usage
To check usage cases, please, refer to `tests/Service/JwtManagerTest.php`, `tests/Service/Functional.php`. Those tests explain how JwtManager should be used in your application.

# Development
## install env
```
docker build -t lib-radix-trie .
docker run -m 200m --cpus 0.3 --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie composer i
```
## testing
```
docker run -m 200m --cpus 0.3 --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie vendor/bin/phpunit
```

## benchmarking
```
docker run --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie php tests/benchmark.php
```

## testing with xdebugging (xdebug on 9001 by default)
```
docker run -m 200m --cpus 0.3 --rm -it --add-host=host.docker.internal:host-gateway -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie vendor/bin/phpunit
```
