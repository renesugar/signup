#! /bin/bash

rsync -varp ./index.html $1
rsync -varp ./signup.php $1
rsync -varp ./webhook.php $1
rsync -varp ./plans.json $1
rsync -varp ./subscriptions.json $1
rsync -varp --exclude 'stylesheets' --exclude 'javascripts' ./public/ $1public/
rsync -varp ./vendor/ $1vendor/
