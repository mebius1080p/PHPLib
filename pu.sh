#!/bin/sh

env XDEBUG_MODE=coverage php ./vendor/bin/phpunit --color $@
