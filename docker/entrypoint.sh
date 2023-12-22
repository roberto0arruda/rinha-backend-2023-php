#!/bin/bash

## Install Laravel
composer install
php artisan migrate:fresh
php-fpm
