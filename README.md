# Google Sheets API v4 for Laravel

[![Build Status](https://travis-ci.org/kawax/laravel-google-sheets.svg?branch=master)](https://travis-ci.org/kawax/laravel-google-sheets)

## Prior notice 3.0

- require `PHP>=7.0` and Laravel 5.5
- Change namespace. maybe `Revolution\Google\Sheets\`. It will auto resolved by Package discovery.

## Installation

### Composer
```
composer require revolution/laravel-google-sheets
```

### Laravel

1. This package depends on https://github.com/pulkitjalan/google-apiclient

2. Add to `providers` in `config/app.php` (not necessary in Laravel 5.5)

```
        PulkitJalan\Google\GoogleServiceProvider::class,
        GoogleSheets\Providers\SheetsServiceProvider::class,
```


3. Add to `aliases` in `config/app.php` (not necessary in Laravel 5.5)

```
        'Google' => PulkitJalan\Google\Facades\Google::class,
        'Sheets' => GoogleSheets\Facades\Sheets::class,
```


4. Run `php artisan vendor:publish --provider="PulkitJalan\Google\GoogleServiceProvider" --tag="config"` to publish the google config file

Edit `config/google.php`

```php
    'client_id'        => env('GOOGLE_CLIENT_ID', ''),
    'client_secret'    => env('GOOGLE_CLIENT_SECRET', ''),
    'redirect_uri'     => env('GOOGLE_REDIRECT', ''),
    'scopes'           => [\Google_Service_Sheets::DRIVE, \Google_Service_Sheets::SPREADSHEETS],
    'access_type'      => 'online',
    'approval_prompt'  => 'auto',
    'prompt'           => 'consent', //"none", "consent", "select_account" default:none
```

5. (Optional) Get API Credentials from https://developers.google.com/console

6. Configure .env as needed

        GOOGLE_APPLICATION_NAME=
        GOOGLE_CLIENT_ID=
        GOOGLE_CLIENT_SECRET=
        GOOGLE_REDIRECT=
        GOOGLE_DEVELOPER_KEY=
        GOOGLE_SERVICE_ENABLED=
        GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION=

## Usage

|id|name|mail|
|---|---|---|
|1|name1|mail1|
|2|name2|mail2|

https://docs.google.com/spreadsheets/d/{spreadsheetID}/...

### Laravel example1
```php
use Sheets;
use Google;

Sheets::setService(Google::make('sheets'));
Sheets::spreadsheet('spreadsheetId');

// all() returns array
$values = Sheets::sheet('Sheet 1')->all();
[
  ['id', 'name', 'mail'],
  ['1', 'name1', 'mail1'],
  ['2', 'name1', 'mail2']
]
```

### Laravel example2
```php
// get() returns Laravel Collection
$rows = Sheets::sheet('Sheet 1')->get();

$header = $rows->pull(0);
$values = Sheets::collection($header, $rows);
$values->toArray()
[
  ['id' => '1', 'name' => 'name1', 'mail' => 'mail1'],
  ['id' => '2', 'name' => 'name2', 'mail' => 'mail2']
]

```
view
```php
@foreach($values as $value)
  {{ array_get($value, 'name') }}
@endforeach
```

### example3 not Laravel
```php
use GoogleSheets\Sheets;

$client = \Google_Client();
$client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
// setup Google Client
// ...

$service = new \Google_Service_Sheets($client);

$sheets = new Sheets();
$sheets->setService($service);

$values = $sheets->spreadsheet('spreadsheetID')->sheet('Sheet 1')->all();
```

### example4 A1 notation
```php
$values = Sheets::sheet('Sheet 1')->range('A1:B2')->all();
[
  ['id', 'name'],
  ['1', 'name1'],
]
```

### example5 update
```php
Sheets::sheet('Sheet 1')->range('A4')->update([['3', 'name3', 'mail3']]);
$values = Sheets::range('')->all();
[
  ['id', 'name', 'mail'],
  ['1', 'name1', 'mail1'],
  ['2', 'name1', 'mail2'],
  ['3', 'name3', 'mail3']
]
```

### example6 append
```php
Sheets::sheet('Sheet 1')->range('')->append([['3', 'name3', 'mail3']]);
$values = Sheets::range('')->all();
[
  ['id', 'name', 'mail'],
  ['1', 'name1', 'mail1'],
  ['2', 'name1', 'mail2'],
  ['3', 'name3', 'mail3']
]
```

## Use original Google_Service_Sheets
```php
$sheets->spreadsheets->...
$sheets->spreadsheets_sheets->...
$sheets->spreadsheets_values->...

Sheets::getService()->spreadsheets->...

```
see https://github.com/google/google-api-php-client-services/blob/master/Sheets.php

## Local Testing
- composer install
- Create new Google Spreadsheet for testing. **Don't use Important Sheets.**
- Put API Credentials(Service Account Key) to `tests/data/test-credentials.json`
- Copy `test-config-sample.php`, rename to `test-config.php`

```
<?php
$this->spreadsheetId = '{SpreadsheetID}';
$this->spreadsheetTitle = 'Test Spreadsheet';
$this->sheetTitle = 'Sheet 1';
$this->sheetId = 0;

```

- Run phpunit

## LICENSE
MIT  
Copyright kawax
