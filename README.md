# FileDownload
A PHP class for any file download. No need to set file headers

```php
$url = 'SOME_FILE_URL'; 
$redirect = 'REDIRECT_LINK_AFTER_DOWNLOAD'; //optional
$File = (is_null($redirect)) ? new FileDownload($url) : new FileDownload($url, $redirect);
$File->setFileName('MY_CUSTOM_FILE_NAME'); // optional
$file->download();// this will then download your file
// The process may result to errors. you can get error messages by:
echo ($file->status['errored']) ? $file->status['message'] : $file->download();
```
