#!/bin/bash

set -e

rm -f FC_DarwinPricing.tar
rm -f FC_DarwinPricing.tgz
rm -f var/connect/FC_DarwinPricing.xml

tar -cvf FC_DarwinPricing.tar ./app/*

if [ ! -d "../MagentoTarToConnect" ]; then
    ( cd .. && git clone https://github.com/astorm/MagentoTarToConnect.git )
fi

php ../MagentoTarToConnect/magento-tar-to-connect.php package.php

rm -f FC_DarwinPricing.tar
