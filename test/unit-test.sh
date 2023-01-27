#!/bin/bash -e -x

exec 2>&1

mysql -v -uroot -h127.0.0.1 < test/ddl/mysql/0010_create_database.sql
mysql -v -uroot -h127.0.0.1 < test/ddl/mysql/0020_create_user.sql
