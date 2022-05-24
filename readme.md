# PaymentBackend

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
composer require adiechahk/payment-backend
```

Publish the vendor
```bash
php artisan vendor:publish --provider=Adiechahk\PaymentBackend\PaymentServiceProvider
```


## Usage

This package is need to be used with frontend package. [package-frontend](https://github.com/AdiechaHK/pkg-payment-frontend)

You need to except routes for cors
just add the following route in the `cors.php` config
`payment/*` to `paths`

```php
...

'paths' => [..., 'payment/*'],

...
```

also make sure you have set your secret key in `.env` file

```
STRIPE_SECRET=
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email adiechahari@email.com instead of using the issue tracker.

## Credits

- [AdiechaHK][link-author]

## License

MIT. Please see the [license file](license.md) for more information...

[ico-version]: https://img.shields.io/packagist/v/adiechahk/payment-backend.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/adiechahk/payment-backend.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/adiechahk/payment-backend/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/adiechahk/payment-backend
[link-downloads]: https://packagist.org/packages/adiechahk/payment-backend
[link-travis]: https://travis-ci.org/adiechahk/payment-backend
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/adiechahk
