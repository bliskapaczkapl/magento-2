# docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -v ~/.composer:/tmp/composer -e COMPOSER_HOME=/tmp/composer composer install --no-dev

# $VENDOR_DIR/bin/security-checker security:check ./composer.lock

docker run --rm --volume `pwd`:/project registry-1.divante.pl:5000/devops/phpcs --colors --exclude=PSR2.Methods.MethodDeclaration,PSR2.Classes.PropertyDeclaration --extensions=php --standard=PSR2,Security --ignore=vendor/* -s .
docker run --rm --volume `pwd`:/project jolicode/phaudit phpmd ./ text codesize --exclude vendor
# docker run --rm --volume `pwd`:/project jolicode/phaudit phpcpd --exclude vendor ./
docker run --rm --volume `pwd`:/project registry-1.divante.pl:5000/devops/phpdoccheck --directory=./ --exclude=vendor
docker run --rm --volume `pwd`:/project jolicode/phaudit phploc .
# $VENDOR_DIR/bin/phpunit --bootstrap dev/tests/bootstrap.php dev/tests/unit/

# docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -v ~/.composer:/tmp/composer -e COMPOSER_HOME=/tmp/composer composer install --no-dev

# docker validator
