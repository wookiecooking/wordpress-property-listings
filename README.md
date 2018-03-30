# atpropertylistings

Simple real estate plugin

### Using Docker Composer

Using docker, we can start up instances of wordpress and mariadb, wordpress
will be need to be considered, but linking is already completed.

```bash
docker-compose build
docker-compose run
```

activate the plugin, this should automatically provision custom post types and 
taxonomies on activation. 

