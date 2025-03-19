#!/usr/bin/env bash
# Releases a new tag.
read -p "Enter tag version: " version
git pull
git rm -r --cached .
git add --all :/
git commit -am "$version"
git push
git tag -a $version -m "$version"
git push origin $version