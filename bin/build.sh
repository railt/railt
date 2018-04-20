#!/usr/bin/env bash
git subsplit init git@github.com:railt/railt.git
git subsplit publish --heads="master 1.x" --no-tags src/SDL:git@github.com:railt/sdl.git
git subsplit publish --heads="master 1.x" --no-tags src/Reflection:git@github.com:railt/reflection.git
git subsplit publish --heads="master 1.x" --no-tags src/Container:git@github.com:railt/container.git
rm -rf .subsplit/
