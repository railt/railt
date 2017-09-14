#!/usr/bin/env bash
git subsplit init git@github.com:railt/railt.git
git subsplit publish --heads="master" --no-tags src/Railt/Container:git@github.com:railt/container.git
git subsplit publish --heads="master" --no-tags src/Railt/Http:git@github.com:railt/http.git
git subsplit publish --heads="master" --no-tags src/Railt/Parser:git@github.com:railt/parser.git
git subsplit publish --heads="master" --no-tags src/Railt/Reflection:git@github.com:railt/reflection.git
git subsplit publish --heads="master" --no-tags src/Railt/Routing:git@github.com:railt/routing.git
git subsplit publish --heads="master" --no-tags src/Railt/Support:git@github.com:railt/support.git
git subsplit publish --heads="master" --no-tags src/Railt/Events:git@github.com:railt/events.git
rm -rf .subsplit/
