#!/bin/bash

# This script is comparing your current changes with the branch name passed as an argument.
# The list of changed files is passed to php-cs-fixer which checks code style.
# For Travis CI/CD we can use different variables that can be found at https://docs.travis-ci.com/user/environment-variables/

# Disable fixer for custom travis builds. In this case $TRAVIS_PULL_REQUEST_BRANCH variable will be empty
if [ "$1" == 'travis' ] && [ -z "$TRAVIS_PULL_REQUEST_BRANCH" ]; then
  exit
fi

if [ $# -eq 0 ]; then
    echo "Please provide target branch."
    exit
fi

if [ "$1" == 'travis' ]; then
  TARGET=$TRAVIS_BRANCH
  COMPARED_BRANCH=$TRAVIS_PULL_REQUEST_BRANCH
elif [ "$1" == 'github-actions' ]; then
  TARGET=$GITHUB_BASE_REF
  COMPARED_BRANCH=$GITHUB_HEAD_REF
else
  if [[ $1 ]]; then
    TARGET=$1
    COMPARED_BRANCH=$(git rev-parse --abbrev-ref HEAD)
  fi

  UNCOMMITTED_FILES=$(git diff --name-only | tr "\n" " ")
fi

git fetch origin "$TARGET"
git fetch origin "$COMPARED_BRANCH"

# changed files included committed and uncommitted ones
CHANGED_FILES="$(git diff --name-only --diff-filter=AM origin/"$TARGET"...origin/"$COMPARED_BRANCH" -- '*.php' | tr "\n" " ") $UNCOMMITTED_FILES"

if [[ $CHANGED_FILES ]]; then
  php -dmemory_limit=-1 vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php -v \
    --dry-run \
    --using-cache=no \
    --show-progress=dots \
    --diff \
    --path-mode=override $CHANGED_FILES
else
  echo "No files to check."
fi
