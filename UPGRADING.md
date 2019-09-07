# UPGRADING

## 4.x to 5.0
- require `PHP>=7.2`

## 3.x to 4.0
- require `PHP>=7.1.3` and Laravel 5.8

## 2.x to 3.0
- require `PHP>=7.0` and Laravel 5.5
- Change namespace to `Revolution\Google\Sheets\`. It will auto resolved by Package discovery.
- composer.json
```
        "revolution/laravel-google-sheets": "^3.0"
```

## 1.0.x to 2.0
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
