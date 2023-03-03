# Testing

## Facade
```php
use Revolution\Google\Sheets\Facades\Sheets;

$spreadsheets = Sheets::setAccessToken($token)
                        ->spreadsheetList();
```

test
```php
Sheets::shouldReceive('setAccessToken->spreadsheetList')->once()->andReturn([]);
```

## trait

```php
$spreadsheets = $request->user()
                        ->sheets()
                        ->spreadsheetList();
```

test
```php
Sheets::shouldReceive('setAccessToken->spreadsheetList')->once()->andReturn([]);
```
