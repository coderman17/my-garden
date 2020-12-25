#!/bin/bash
sleep 10
mysql -h "localhost" -u "root" -p"password" < "/etc/mysql/scripts/migration.sql"
