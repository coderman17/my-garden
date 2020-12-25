# my-garden
A webapp to visualise your garden
## Installing
- `git clone https://github.com/coderman17/my-garden.git`
- From api directory: `composer install`
- From ui\my-garden directory: `npm install`
- Copy `api\.env.example.php` to `api\.env.php` and alter the password if you wish to change it (you'll need to take additional steps to change it on the mysql container)
## Running
- run docker containers: `docker-compose up -d`
- sh into mysql container: `docker exec -ti mysql sh`
- run migration script: `mysql -h "localhost" -u "root" -p"password" < /etc/mysql/scripts/migration.sql`
## Useful commands
- Kill existing mysql.exe (windows): `taskkill /F /IM mysqld.exe`
