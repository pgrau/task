#!/bin/sh

DOCKER_EXEC="docker exec -it task-php"
DOCKER_COMPOSE="docker-compose"
PHPUNIT_CMD="php -d memory_limit=-1 vendor/bin/phpunit --color=always"

start() {
    ${DOCKER_COMPOSE} up -d
}

down() {
    ${DOCKER_COMPOSE} down
}

configure() {
    ${DOCKER_EXEC} composer install
    ${DOCKER_EXEC} bin/console db:create:db
    ${DOCKER_EXEC} bin/console db:create:schema
    ${DOCKER_EXEC} bin/console message:create:exchange
}

testAll() {
    ${DOCKER_EXEC} ${PHPUNIT_CMD}
}

testAllWithMetrics() {
    ${DOCKER_EXEC} ${PHPUNIT_CMD} --coverage-html metrics
}

quality() {
    ${DOCKER_EXEC} composer phpcbf
    ${DOCKER_EXEC} composer phpstan
}

case "$1" in
  test)
    testAll
    ;;
  metrics)
    testAllWithMetrics
    ;;
  quality)
    quality
    ;;
  start)
    start
    ;;
  down)
    down
    ;;
  configure)
    configure
    ;;
  *)
    echo "Usage: $0 {start|down|configure|test|metrics|quality}"
esac