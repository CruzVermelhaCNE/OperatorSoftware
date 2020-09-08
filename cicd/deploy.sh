#!/bin/sh
set -e

##vendor/bin/phpunit

(git push) || true

git checkout deploy
git merge master

git push origin deploy

git checkout master