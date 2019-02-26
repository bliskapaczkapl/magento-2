VENDOR_DIR=vendor

# docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -v ~/.composer:/tmp/composer -e COMPOSER_HOME=/tmp/composer composer install --no-dev

$VENDOR_DIR/bin/security-checker security:check ./composer.lock
$VENDOR_DIR/bin/phpcs --config-set colors 1
$VENDOR_DIR/bin/phpcs --exclude=PSR2.Methods.MethodDeclaration,PSR2.Classes.PropertyDeclaration --extensions=php --standard=PSR2 --ignore=app/code/Sendit/Bliskapaczka/vendor/* -s app/code/Sendit/Bliskapaczka/
$VENDOR_DIR/bin/phpmd app/code/Sendit/Bliskapaczka/ text codesize --exclude app/code/Sendit/Bliskapaczka/vendor
$VENDOR_DIR/bin/phpcpd --exclude vendor app/code/Sendit/Bliskapaczka/
$VENDOR_DIR/bin/phpdoccheck --directory=app/code/Sendit/Bliskapaczka
$VENDOR_DIR/bin/phploc app/code/Sendit/Bliskapaczka
# $VENDOR_DIR/bin/phpunit --bootstrap dev/tests/bootstrap.php dev/tests/unit/

# docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -v ~/.composer:/tmp/composer -e COMPOSER_HOME=/tmp/composer composer install --no-dev
