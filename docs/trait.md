# GoogleSheets Trait
Like a Laravel Notifications.

Add `GoogleSheets` trait to User model.

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Revolution\Google\Sheets\Traits\GoogleSheets;

class User extends Authenticatable
{
    use Notifiable;
    use GoogleSheets;

    /**
     * Get the Access Token
     *
     * @return string
     */
    protected function sheetsAccessToken()
    {
        return $this->access_token;
    }
}
```

Add `sheetsAccessToken()`(abstract) for access_token.

Trait has `sheets()` that returns Sheets instance.

```php
    public function __invoke(Request $request)
    {
        // Facade
        //        $token = $request->user()->access_token;
        //
        //        Google::setAccessToken($token);
        //
        //        $spreadsheets = Sheets::setService(Google::make('sheets'))
        //                              ->setDriveService(Google::make('drive'))
        //                              ->spreadsheetList();

        // GoogleSheets Trait
        $spreadsheets = $request->user()
                                ->sheets()
                                ->spreadsheetList();

        return view('sheets.index')->with(compact('spreadsheets'));
    }
```
