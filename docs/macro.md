# Macroable

Extend any method by your self.

## Register in AppServiceProvider.php

```php
    public function boot()
    {
        \Sheets::macro('my', function () {
            return $this->getService()->spreadsheets->...
        });
    }
```

## Use somewhere
```php
$values = \Sheets::sheet('Sheet 1')->my();
```
