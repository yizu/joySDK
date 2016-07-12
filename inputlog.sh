#!/bin/sh
data=`date +%F`
for i in `seq -w 15 16`;do
/Applications/XAMPP/xamppfiles/bin/mysql -u root -proot <<EOF
use joy_user_1;
load data local infile "/Applications/XAMPP/xamppfiles/htdocs/joysdk/application/logs/login/login_${data}.${i}.log"  into table loginrecord FIELDS TERMINATED BY ',';
EOF
done