# Plugin for wallabag: Download web.archive.org version if we have 404 

This bundle allows you to find snapshot in web.archive.org website

## Requirements

* wallabag >= 2.2.2

## Installation

### Download the bundle

```
composer require nicosomb/wallabag-webarchive-bundle
```

### Enable the bundle

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Nicosomb\WallabagWebarchiveBundle\NicosombWallabagWebarchiveBundle(),
        );

        // ...
    }

    // ...
}
```

### Configure your application

```yml
# app/config/config.yml

nicosomb_wallabag_webarchive:
    enabled: true
```
