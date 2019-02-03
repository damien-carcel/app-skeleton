#!/usr/bin/env bash

cd ./back

max_counter=45

counter=1
while ! docker-compose exec mysql mysql --protocol TCP -uroot -proot -e "show databases;" > /dev/null 2>&1; do
    sleep 1
    counter=`expr ${counter} + 1`
    if [[ ${counter} -gt ${max_counter} ]]; then
        >&2 echo "We have been waiting for MySQL too long already; failing."
        exit 1
    fi;
done
