# Green-PHP-SDK (In Development)

## Please Note: This is not the official SDK for Green Money.

If you have any questions regarding this package, please create an issue on this repository.
If you have any questions regarding the official PHP SDK or the API integration, please directly reach out to https://www.green.money/

A fully featured PHP SDK for calling methods from the Green Payment Processing API and parsing the responses. The eCheck API allows for complete integration of your application into our real-time check entry system.

[Development Plan](https://github.com/greenlystapp/green.money-php-sdk/issues/3)
## Installation

```
composer require greenlystapp/green.money
```

### Imports

```
require 'vendor/autoload.php';

use Greenlyst\GreenMoney\ECheck;
```

### Sample Code

```
$eCheck = new ECheck($clientId, $apiPassword, $live = true);
$eCheck->getCustomer($payorId);
```
