#!/bin/bash

cd ../../../
php vendor/bin/phinx migrate -c vendor/balancer/infonesy-driver-zerotalk/phinx.yml

