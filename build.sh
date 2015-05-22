#!/bin/bash

set -e

rm -f Uplift_DecisionEngine.tar
rm -f Uplift_DecisionEngine.tgz
rm -f var/connect/Uplift_DecisionEngine.xml

tar -cvf Uplift_DecisionEngine.tar ./app/*

if [ ! -d "../MagentoTarToConnect" ]; then
    ( cd .. && git clone https://github.com/astorm/MagentoTarToConnect.git )
fi

php ../MagentoTarToConnect/magento-tar-to-connect.php package.php

rm -f Uplift_DecisionEngine.tar
