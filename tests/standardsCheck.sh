#!/bin/bash
# Check those standards!

/usr/bin/phpcs --standard=./CodingStandards/ ./../html/ --tab-width=4 --extensions=php
