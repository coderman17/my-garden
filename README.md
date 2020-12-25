# my-garden
A webapp to visualise your garden
## Installing
- `git clone https://github.com/coderman17/my-garden.git`
- From api directory: `composer install`
- From ui/my-garden directory: `npm install`
- If you wish to change the password in `api/.env.php` then do so (you'll need to take additional steps to change it on the mysql container too)
## Running
- run docker containers: `docker-compose up -d`
- sh into mysql container: `docker exec -ti mysql sh`
- run migration script: `mysql -h "localhost" -u "root" -p"password" < /etc/mysql/scripts/migration.sql`
## Useful commands
- Kill existing mysql.exe (windows): `taskkill /F /IM mysqld.exe`
