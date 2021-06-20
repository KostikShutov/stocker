# stocker

Software system for stock prediction

## Requirements
* php 8.0
* nodejs 16.2
* mysql 8.0
* redis 5.0
* python 3.8

### Composer and nodejs install
```
composer install
npm install
```

### Build assets
```
npm run build
```

### Execute migrations
```
php bin/console doc:mig:mig
```

### Download stocks from provider with date interval
```
php bin/console app:stocks:download --help
php bin/console app:stocks:download --provider yahoo --start 01.04.2020 --end 01.04.2021
```

### Run consumer
```
php bin/console messenger:consume async
```
