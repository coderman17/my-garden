# my-garden
A webapp to visualise your garden
## Running
- run docker containers: `docker-compose up -d`
- sh into mysql container: `docker exec -ti mysql sh`
- run migration script: `mysql -h "localhost" -u "root" -p"password" < /etc/mysql/scripts/migration.sql`
## Useful commands
- Kill existing mysql.exe (windows): `taskkill /F /IM mysqld.exe`
