# User
Lib called to provide https://en.wikipedia.org/wiki/Radix_tree impelementation for php

## usage
To check usage cases, please, refer to `tests/InserterTest.php`, `tests/FinderTest.php`, `tests/DeleterTest.php`, `tests/CompressorTest.php`.

# Development
## install env
```
docker build -t lib-radix-trie .
docker run -m 200m --cpus 0.3 --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie composer i
```
## testing
```
docker run -eXDEBUG_MODE=off -m 200m --cpus 0.3 --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie vendor/bin/phpunit
```

## benchmarking
```
docker run -eXDEBUG_MODE=off --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie php tests/benchmarks/benchmark.php
```

## testing with xdebugging (xdebug on 9001 by default)
```
docker run -m 200m --cpus 0.3 --rm -it --add-host=host.docker.internal:host-gateway -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie vendor/bin/phpunit
```

## profiling
```
docker run --rm -it -u $(id -u):$(id -g) -w /tmp -v ${PWD}:/tmp lib-radix-trie php -d xdebug.mode=profile -d xdebug.output_dir=.vscode tests/benchmarks/benchmark.php
docker run --rm -u ${UID} -v ${PWD}/.vscode:/tmp --name webgrind -p 8081:80 jokkedk/webgrind:latest
```
