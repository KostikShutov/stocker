# stocker

Software system for stock prediction

## Requirements
* php 8.0
* nodejs 16.x
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
php bin/console st:download --help
php bin/console st:download -d memory_limit=-1 --provider yahoo --start 01.01.2000 --end 01.07.2021
```

### Run consumer
```
php bin/console messenger:consume async
```

### Accuracy check
```
php bin/console acc:check --help
php bin/console acc:check --start 01.01.2010 --end 01.01.2015 --method long_short_term_memory
```
