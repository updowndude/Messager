#!/bin/sh
set -ex
hhvm --version
echo hhvm.jit=0 >> /etc/hhvm/php.ini
apt-get update -y
apt-get install -y wget curl git
curl https://getcomposer.org/installer | hhvm --php -- /dev/stdin --install-dir=/usr/local/bin --filename=composer

cd /var/source
hhvm /usr/local/bin/composer install

hh_server --check $(pwd)
hhvm vendor/bin/phpunit tests/

HHVM_VERSION=$(hhvm --php -r 'echo HHVM_VERSION_ID;' 2>/dev/null);
if [ $HHVM_VERSION -ge 32002 ]; then
  hhvm -d hhvm.php7.all=1 -d hhvm.jit=0 vendor/bin/phpunit tests/
fi

if [ $HHVM_VERSION -ge 32200 -a $HHVM_VERSION -lt 32300 ]; then
  echo enable_experimental_tc_features = optional_shape_field >> .hhconfig
  hh_server --check $(pwd)
fi
