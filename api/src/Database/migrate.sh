#!/bin/bash
mysql -h "localhost" -u "root" -p"password" < "/etc/mysql/scripts/migration.sql"
