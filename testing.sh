#!/usr/bin/env bash
log="codecept-result.log"
finished=false
touch ${log}
echo -n "" >${log}
vagrant ssh -c "cd /var/www; php artisan config:clear; php artisan migrate; php codecept.phar clean; php codecept.phar build; php codecept.phar run --coverage-html --coverage-xml;" > ${log} 2>&1
cat ${log}
if grep "FAILURES!" ${log}
    then
       echo "TESTS FAILED!"; exit 1;
    fi