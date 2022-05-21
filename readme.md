# Fehape
### Build and Install requirements

#### Changing the user rights
```sh
chmod +x build.sh
```

#### Run docker-compose with build.sh file
```sh
./build.sh
```

#### installing new library
```sh
docker-compose exec fehape composer newlibrary/name
```

#### Port and Url used
**src/www_public**
```sh
http://localhost:8000/
```
**src/www_admin**
```sh
http://localhost:8001/
```

static files **src/static**
```sh
http://localhost:8000/static/
```
```sh
http://localhost:8001/static/
```

## Plugins and libraries
* [**Twig** - The flexible, fast, and secure template engine for PHP]("https://twig.symfony.com/")
* [**FastRoute** - Fast request router for PHP]("https://github.com/nikic/FastRoute")