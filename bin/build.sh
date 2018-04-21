#!/usr/bin/env bash
git subsplit init git@github.com:railt/railt.git
git subsplit publish --heads="master 1.x" --no-tags src/SDL:git@github.com:railt/sdl.git
rm -rf .subsplit/
