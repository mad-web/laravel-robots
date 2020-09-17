# Robots.txt generator service

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-style]][link-style]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Nice Robots.txt generator service. For more information see [www.robotstxt.org](http://www.robotstxt.org)

## Installation

You can install the package via composer:

```bash
composer require mad-web/laravel-robots
```

## Usage

Setting a condition in your `AppServiceProvider` that determines whether the site should be indexed.
```php
use MadWeb\Robots\RobotsFacade;

public function boot()
{
    RobotsFacade::setShouldIndexCallback(function () {
        return app()->environment('production');
    });
    ...
}
```

You can create simple single action controller for generating robots.txt

**/routes/web.php**
```php
Route::get('robots.txt', 'RobotsController');
```
**/app/Http/Controllers/Robots**
```php
namespace App\Http\Controllers;

use MadWeb\Robots\Robots;
use App\Http\Controllers\Controller;

class RobotsController extends Controller
{
    /**
     * Generate robots.txt
     */
    public function __invoke(Robots $robots)
    {
        $robots->addUserAgent('*');

        if ($robots->shouldIndex()) {
            // If on the live server, serve a nice, welcoming robots.txt.
            $robots->addDisallow('/admin');
            $robots->addSitemap('sitemap.xml');
        } else {
            // If you're on any other server, tell everyone to go away.
            $robots->addDisallow('/');
        }

        return response($robots->generate(), 200, ['Content-Type' => 'text/plain']);
    }
}
```

Add robots meta tag into your view inside the `<head>` tag
```html
<head>
	{!! Robots::metaTag() !!}
</head>
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email madweb.dev@gmail.com instead of using the issue tracker.

## Credits

- [mad-web](https://github.com/mad-web)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mad-web/laravel-robots.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/mad-web/laravel-robots/master.svg?style=flat-square
[ico-style]: https://styleci.io/repos/107463951/shield
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/mad-web/laravel-robots.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/mad-web/laravel-robots.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mad-web/laravel-robots.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mad-web/laravel-robots
[link-travis]: https://travis-ci.org/mad-web/laravel-robots
[link-style]: https://styleci.io/repos/107463951
[link-scrutinizer]: https://scrutinizer-ci.com/g/mad-web/laravel-robots/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/mad-web/laravel-robots
[link-downloads]: https://packagist.org/packages/mad-web/laravel-robots
[link-author]: https://github.com/mad-web
[link-contributors]: ../../contributors
