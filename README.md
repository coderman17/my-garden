# my-garden
A webapp to help visualise and plan your garden

# Installation
  - clone the repo
  - run `composer install` from /api
  - run `npm install` from /ui/my-garden

# Running
  - run `docker-compose up -d` from the root my-garden directory
  - wait some time for the containers to finish spinning up
  - you may need to run this for the next step to work `docker exec -u root mysql chmod 777 /etc/mysql/scripts/migrate.sh`
  - run `docker exec -u root mysql /etc/mysql/scripts/migrate.sh`
  - you should then see the interface at localhost:80

# Use
The UI should be fairly self-explanatory, but essentially you can add and edit your plant collection, using images from the web.  
A small plant collection is already pre-loaded.  
You can then use your plants to design or visualise your gardens
