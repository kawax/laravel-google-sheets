# Google Sheets API v4 for Laravel

[![Build Status](https://travis-ci.org/kawax/laravel-google-sheets.svg?branch=master)](https://travis-ci.org/kawax/laravel-google-sheets)

## Install

### Composer
```
composer require revolution/laravel-google-sheets
```

### Laravel
```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kawax/google-apiclient"
        }
    ],
```

```
composer require pulkitjalan/google-apiclient:dev-master

```

config/google.php  
https://github.com/pulkitjalan/google-apiclient  
https://github.com/kawax/google-apiclient

config/app.php  

```
GoogleSheets\Providers\SheetsServiceProvider::class,
```

```
'Sheets' => GoogleSheets\Facades\Sheets::class,
```

## Usage

|id|name|mail|
|---|---|---|
|1|name1|mail1|
|2|name2|mail2|

### Laravel example1
```
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
```
// get() returns Laravel Collection
$values = Sheets::sheet('Sheet 1')->get();

$head = $values->pull(0);
$sheets = Sheets::collection($head, $values->toArray());
$sheets->toArray()
[
  ['id' => '1', 'name' => 'name1', 'mail' => 'mail1'],
  ['id' => '2', 'name' => 'name2', 'mail' => 'mail2']
]

```
view
```
@foreach($sheets as $sheet)
  {{ array_get($sheet, 'name') }}
@endforeach
```

### example3 not Laravel
```
use GoogleSheets\Sheets;

$client = Google_Client();
$client->setScopes([Google_Service_Sheets::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
// setup Google Client

$service = new Google_Service_Sheets($client);

$sheets = new Sheets();
$sheets->setService($service);

$values = $sheets->spreadsheet('spreadsheetID')->sheet('Sheet 1')->all();
```

## Use original Google_Service_Sheets
```
$sheets->spreadsheets->...
$sheets->spreadsheets_sheets->...
$sheets->spreadsheets_values->...

Sheets::getService()->spreadsheets->...

```
see https://github.com/google/google-api-php-client-services/blob/master/Sheets.php



## LICENSE
MIT  
Copyright kawax
