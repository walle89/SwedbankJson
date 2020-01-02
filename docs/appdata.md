# App Data
This library is using Swedbank's Mobile Apps API, that Swedbank in Sweden and Sparbankerna are using for there Android and Ios apps.
As part each request to the Mobile Apps API, it needs to contain meta data such as the version number and App ID of the mobile app.
Swedbank's API blocks older versions of its apps, and that also means the app data needs to be updated. 

Previous versions of this library this had to be done manually via Composer update.
With version `1.0.0`, this can be done with the updated `AppData` class automatically fetch from a remote source (by default from 
https://raw.githubusercontent.com/walle89/SwedbankJson/files/appdata.json).

## AppData class usage

### Minimal recommended config
Preferred for easier to deploy and maintain. It will automatically download and check for updated AppData.json file.
Make sure the script have file write access (check folder/file permissions).

```php
$cachePath = __DIR__.'/AppData.json';
$appData = new SwedbankJson\AppData($bankApp, $cachePath);
```

Variable `$cachePath` must be a string with full path to the saved cache file.

### Disable cache file
If you for some reason can't or don't want to write to the filesystem, you can skip having cache altogether and fetch AppData.json file remotely each time.

```php
$appData = new SwedbankJson\AppData($bankApp, '');
```

### Use alternative fetch source
If you want to fetch other than the pre-configured source, you can do so:

```php
$cachePath = __DIR__.'/AppData.json'; // Full path to cache file
$cacheExpiration = 1440; // 24 hours in minutes
$remoteUrl = 'https://example.com/my/AppData.json';

$appData  = new SwedbankJson\AppData($bankApp, $cachePath, $cacheExpiration, $remoteUrl);
```

Or configure to always fetch from a server on LAN or any other source over HTTP(S):

```php
$cachePath = '';
$cacheExpiration = 0;
$remoteUrl = 'https://10.0.0.1:8080/my/AppData.json';

$appData  = new SwedbankJson\AppData($bankApp, $cachePath, $cacheExpiration, $remoteUrl);
```

### Adjust cache expiration 
Standard is once per day (1440 minutes). You can change this to any positive int, reasserting number of minutes. 0 = never expire, 1 = expire one minute after last modification.

### Disable remote download 
This is useful if you want to download and update the AppData.json cache on the side (eg. via cron job), or deploy it as part of a docker image build. 

```php
$cachePath = '/var/cache/AppData.json'; // Full path to cache file
$cacheExpiration = 0; // Never expire
$appData = new SwedbankJson\AppData($bankApp, $cachePath, $cacheExpiration);
```