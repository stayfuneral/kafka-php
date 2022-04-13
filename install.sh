#!/bin/bash

cp .env.example .env

docker-compose up -d --build

cd app

composer install --ignore-platform-reqs