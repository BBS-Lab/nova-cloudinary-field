# Laravel Nova Cloudinary field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bbs-lab/nova-cloudinary-field.svg?style=flat-square)](https://packagist.org/packages/bbs-lab/nova-cloudinary-field)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/bbs-lab/nova-cloudinary-field/master.svg?style=flat-square)](https://travis-ci.org/bbs-lab/nova-cloudinary-field)
[![StyleCI](https://styleci.io/repos/217854455/shield)](https://styleci.io/repos/217854455)
[![Quality Score](https://img.shields.io/scrutinizer/g/bbs-lab/nova-cloudinary-field.svg?style=flat-square)](https://scrutinizer-ci.com/g/bbs-lab/nova-cloudinary-field)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/bbs-lab/nova-cloudinary-field/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/bbs-lab/nova-cloudinary-field/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/bbs-lab/nova-cloudinary-field.svg?style=flat-square)](https://packagist.org/packages/bbs-lab/nova-cloudinary-field)

A Cloudinary Media Library field for Laravel Nova.

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Screenshots](#screenshots)
    - [List view](#list-view)
    - [Detail view](#detail-view)
    - [Form view](#form-view)
- [Changelog](#changelog)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require bbs-lab/nova-cloudinary-field
```

The package will automatically register itself.

You can publish the config-file with:

```bash
php artisan vendor:publish --provider="BBSLab\CloudinaryField\CloudinaryFieldServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloud Name
    |--------------------------------------------------------------------------
    |
    | This is the name of your Cloudinary cloud name
    | It can commonly be found on the upper left part of the Cloudinary
    | dashboard.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME',''),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | This is your public Cloudinary API key
    | It can commonly be found on the upper left part of the Cloudinary
    | dashboard.
    |
    */

    'api_key' => env('CLOUDINARY_API_KEY',''),

    /*
    |--------------------------------------------------------------------------
    | API Secret
    |--------------------------------------------------------------------------
    |
    | This is your secret Cloudinary key
    | It can commonly be found on the upper left part of the Cloudinary
    | dashboard (remember to click on "Reveal")
    |
    */

    'api_secret' => env('CLOUDINARY_API_SECRET',''),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Username
    |--------------------------------------------------------------------------
    |
    | This is the email address of the Cloudinary account you want to use.
    |
    */

    'username' => env('CLOUDINARY_USERNAME',''),
];
```


## Usage

You can use the `BBSLab\CloudinaryField\Cloudinary` field in your Nova resource:

```php
<?php

namespace App\Nova;

use BBSLab\CloudinaryField\Cloudinary;

class BlogPost extends Resource
{
    // ...
    
    public function fields(Request $request)
    {
        return [
            // ...

            Cloudinary::make('Image'),

            // ...
        ];
    }
    
}
```

## Screenshots

### List view

![List view](screenshots/screenshot_list.png?raw=true "How image appears on list view")

### Detail view

![Detail view](screenshots/screenshot_detail.png?raw=true "How image appears on detail view")

### Form view

Form view - Nothing selected
![Form view - Nothing selected](screenshots/screenshot_edit_novalue.png?raw=true "How image appears on form view with no picture selected")

Form view - Picture selected
![Form view - Picture selected](screenshots/screenshot_edit_selected.png?raw=true "How image appears on form view with picture selected")

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email paris@big-boss-studio.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Lionel Blessig](https://github.com/wasitum)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
