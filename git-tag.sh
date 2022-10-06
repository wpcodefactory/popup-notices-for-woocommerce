#!/usr/bin/env bash
# Releases a new tag.
read -p "Enter tag version: " version
git add --all :/
git commit -am "$version"
git push
git tag -a $version -m "$version"
git push origin $version