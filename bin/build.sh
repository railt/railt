#!/usr/bin/env bash
git subsplit init git@github.com:railt/railt.git
git subsplit publish --heads="master" --no-tags src/Container:git@github.com:railt/container.git
git subsplit publish --heads="master" --no-tags src/Http:git@github.com:railt/http.git
git subsplit publish --heads="master" --no-tags src/Compiler:git@github.com:railt/compiler.git
git subsplit publish --heads="master" --no-tags src/Routing:git@github.com:railt/routing.git
git subsplit publish --heads="master" --no-tags src/Events:git@github.com:railt/events.git
git subsplit publish --heads="master" --no-tags src/Reflection:git@github.com:railt/reflection.git
git subsplit publish --heads="master" --no-tags src/Parser:git@github.com:railt/parser.git
git subsplit publish --heads="master" --no-tags src/Adapters/Webonyx:git@github.com:railt/webonyx-adapter.git
rm -rf .subsplit/
