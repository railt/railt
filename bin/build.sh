#!/usr/bin/env bash
git subsplit init git@github.com:railt/railt.git
git subsplit publish --heads="master 1.x" --no-tags src/Container:git@github.com:railt/container.git
git subsplit publish --heads="master 1.x" --no-tags src/Http:git@github.com:railt/http.git
git subsplit publish --heads="master 1.x" --no-tags src/Compiler:git@github.com:railt/compiler.git
git subsplit publish --heads="master 1.x" --no-tags src/Events:git@github.com:railt/events.git
git subsplit publish --heads="master 1.x" --no-tags src/Reflection:git@github.com:railt/reflection.git
git subsplit publish --heads="master 1.x" --no-tags src/GraphQL:git@github.com:railt/graphql.git
git subsplit publish --heads="master 1.x" --no-tags src/Io:git@github.com:railt/io.git
git subsplit publish --heads="master 1.x" --no-tags src/Storage:git@github.com:railt/storage.git
git subsplit publish --heads="master 1.x" --no-tags src/Adapters/Webonyx:git@github.com:railt/webonyx-adapter.git
rm -rf .subsplit/
