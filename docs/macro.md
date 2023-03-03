# Macroable

Extend any method by your self.

## Register in AppServiceProvider.php

```php
use Revolution\Google\Sheets\Facades\Sheets;

    public function boot()
    {
        Sheets::macro('my', function () {
            return $this->getService()->spreadsheets->...
        });
    }
```

## Use somewhere
```php
use Revolution\Google\Sheets\Facades\Sheets;

$values = Sheets::sheet('Sheet 1')->my();
```
