#!/usr/bin/env bash
VERSION="1.2.0"
cd bin

git clone git@github.com:railt/container.git
cd container && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/http.git
cd http && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/compiler.git
cd compiler && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/events.git
cd events && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/reflection.git
cd reflection && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/graphql.git
cd graphql && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/io.git
cd io && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

git clone git@github.com:railt/storage.git
cd storage && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

# Not yet =)
# git clone git@github.com:railt/webonyx-adapter.git
# cd webonyx-adapter && git pull && git tag -a ${VERSION} -m "Release v${VERSION}" && git push origin ${VERSION} && cd ../

