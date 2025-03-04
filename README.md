# OFX

A simple OFX file reader

![image](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![](https://img.shields.io/badge/PHP-8.3-green)

## Authors

- [@Weslley Araujo](https://www.github.com/WeslleyRAraujo)


## How to use

Installation
```bash
  composer require weslleyraraujo/ofx
```

Use Example
```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use  WeslleyRAraujo\OFX\OFX AS OFXReader;

$OFXReader = new OFXReader(__DIR__.'/ofx.ofx');

$transactionList = $OFXReader->getTransactionList();
$headers = $OFXReader->getHeaders();
```
    