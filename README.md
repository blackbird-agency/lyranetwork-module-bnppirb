# BNPP IRB for Magento

BNPP IRB for Magento is an open source plugin that links e-commerce websites based on Magento to BNPP IRB secured payment gateway developped by [Lyra Network](https://www.lyra-network.com/).

Namely, it enables the following payment methods :
* BNPP IRB - Standard credit card payment
* BNPP IRB - Credit card payment in installments

## Installation & upgrade

- Create app/code/Lyranetwork/Bnppirb folder if not exists.
- Unzip module in your Magento 2 app/code/Lyranetwork/Bnppirb folder.
- Open command line and change to Magento installation root directory.
- Enable module: php bin/magento module:enable --clear-static-content Lyranetwork_Bnppirb
- Upgrade database: php bin/magento setup:upgrade
- Re-run compile command: php bin/magento setup:di:compile
- Update static files by: php bin/magento setup:static-content:deploy [locale]

In order to deactivate the module: php bin/magento module:disable --clear-static-content Lyranetwork_Bnppirb

## Configuration

- In Magento 2 administration interface, browse to "STORES > Configuration" menu
- Click on "Payment Methods" link under "SALES" section
- Expand BNPP IRB payment method to enter your gateway credentials
- Refresh invalidated Magento cache afeter config saved. 

## License

Each BNPP IRB payment module source file included in this distribution is licensed under Open Software License (OSL 3.0).

Please see LICENSE.txt for the full text of the OSL 3.0 license. It is also available through the world-wide-web at this URL: https://opensource.org/licenses/osl-3.0.php.
