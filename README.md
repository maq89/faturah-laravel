# Fatural Laravel

## Installation
Begin by installing this package through Composer. Just run following command to terminal-

```php
composer require maq89/faturah-laravel
```

Once this operation completes, the final step is to add the service provider. Open config/app.php, and add a new item to the providers array.
```php
'providers' => [
	...
	Damas\Faturah\FaturahServiceProvider::class,
],
```

Now add the alias.

```php
'aliases' => [
	...
	'Faturah' => Damas\Faturah\Facades\FaturahFacade::class,
],
```


### Example:
```php
Route::get('/faturah', function () {
    $merchantCode = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxx1012';
    $secureKey = 'ece00dc2-9a57-4403-a86b-a2be3eccae53';
    $faturah = Faturah::getInstance($merchantCode, $secureKey);
    $faturah->order->addItem('2', 'Sumsung', 'Sumsung Mobile 6600 Silver Color', '1', 20);
    //$faturah->order->addItem('1', 'Nokia Mobile', 'Nokia Mobile 6600 Silver Color', '1', 10); // Add another Item
    $faturah->order->customerInfo('cutomer name', 'customer@domain.com', '1234567890', 'en');
    //$faturah->order->deliveryCharges(5); // If you want to charge delivery Charges
    $faturah->send();
    return '';
});
```
