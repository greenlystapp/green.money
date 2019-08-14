#Green-PHP-SDK (In Development)

A fully featured PHP SDK for calling methods from the Green Payment Processing API and parsing the responses. The eCheck API allows for complete integration of your application into our real-time check entry system.

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

### To Do

- [ ] Integrate Notification API
- [ ] Integrate Reports API
- [ ] Create Tests
- [ ] Integrate with a CI