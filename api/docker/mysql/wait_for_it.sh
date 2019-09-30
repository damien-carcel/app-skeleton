#!/usr/bin/env bash

set -e

cd api

COUNTER=1
MAX_COUNTER=45

echo "Waiting for MySQL server…"
while ! docker-compose exec mysql mysql --protocol TCP -uroot -proot -e "show databases;" > /dev/null 2>&1; do
    sleep 1
    COUNTER=$((${COUNTER} + 1))
    if [ ${COUNTER} -gt ${MAX_COUNTER} ]; then
        echo "We have been waiting for MySQL too long already; failing." >&2
        exit 1
    fi;
done

echo "MySQL server is running!"
