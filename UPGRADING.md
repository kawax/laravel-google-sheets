# UPGRADING

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
