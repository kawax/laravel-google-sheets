# Local Testing
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
