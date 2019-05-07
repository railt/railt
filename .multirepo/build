#/bin/bash
git clone https://github.com/SerafimArts/sync-tool.git
cd sync-tool
composer install
cd ../
php sync-tool/packages sync "multirepo.json" -vvv
rm -rf sync-tool
