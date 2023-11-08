#!/bin/bash

php -dpcov.enabled=1 ./vendor/bin/phpunit --coverage-text="./coverage.log" --coverage-php /tmp/coverage.cov

if [ $? -ne 0 ]; then
  exit 1 # exit with failure status if the tests failed
fi

git diff HEAD^1 > /tmp/patch.txt
head -n 10 ./coverage.log # show only coverage summary

# Run the command and capture its output
output=$(./vendor/bin/phpcov patch-coverage --path-prefix "$(pwd)" /tmp/coverage.cov /tmp/patch.txt 2>&1)

# Check the exit status of the previous command
if [ $? -eq 0 ]; then
  exit 0
else
  # Check if the output contains the specific message
  if echo "$output" | grep -q "Unable to detect executable lines that were changed"; then
    exit 0
  else
    echo "$output"
    exit 1
  fi
fi
