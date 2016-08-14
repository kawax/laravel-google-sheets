# Google Sheets API v4 for Laravel

[![Build Status](https://travis-ci.org/kawax/laravel-google-sheets.svg?branch=master)](https://travis-ci.org/kawax/laravel-google-sheets)

## Install

### Composer
```
composer require revolution/laravel-google-sheets
```

### Laravel
config/google.php  
https://github.com/pulkitjalan/google-apiclient

config/app.php  
```php
PulkitJalan\Google\GoogleServiceProvider::class,
GoogleSheets\Providers\SheetsServiceProvider::class,
```

```php
'Google' => PulkitJalan\Google\Facades\Google::class,
'Sheets' => GoogleSheets\Facades\Sheets::class,
```

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

## Use original Google_Service_Sheets
```php
$sheets->spreadsheets->...
$sheets->spreadsheets_sheets->...
$sheets->spreadsheets_values->...

Sheets::getService()->spreadsheets->...

```
see https://github.com/google/google-api-php-client-services/blob/master/Sheets.php

## Upgrade

### to 2.0 from 1.0.x
- Remove "repositories" from composer.json
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kawax/google-apiclient"
        }
    ],
```
- Change composer.json  
Bump version
```json
    "require": {
        "revolution/laravel-google-sheets": "^2.0"
    }
```
Remove
```
        "pulkitjalan/google-apiclient": "^3.0",
```
- Remove "vendor" dir.
- Remove composer.lock
- Clear composer cache. `composer clear-cache`
- `composer install`
- Change config/google.php  
```
    'service' =>  [
        /*
        | Enable service account auth or not.
        */
        'enabled' => false,

        /*
        | Path to service account json file
        */
        'file' => '',
    ],
```
https://github.com/pulkitjalan/google-apiclient#usage

## LICENSE
MIT  
Copyright kawax
