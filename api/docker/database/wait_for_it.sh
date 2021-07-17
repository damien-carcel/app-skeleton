#!/usr/bin/env bash

set -e

cd api

COUNTER=1
MAX_COUNTER=45

echo "Waiting for PostgreSQL server…"
until docker-compose exec database >&2  psql -h "localhost" -U "app_skeleton" -c '\q'; do
  >&2 echo "PostgreSQL is unavailable - sleeping"
  COUNTER=$((${COUNTER} + 1))
  if [ ${COUNTER} -gt ${MAX_COUNTER} ]; then
      echo "We have been waiting for PostgreSQL too long already; failing." >&2
      exit 1
  fi;
  sleep 1
done
>&2 echo "PostgreSQL is up - executing command"

echo "PostgreSQL server is running!"
