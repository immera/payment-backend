# Local setup for development

To make changes in the package and test locally, you need to have existing laravel project setup or you can create new laravel project.

Once you have laravel project ready you can create `pacakges/immera` directory in the root.

then you can clone this repo there.
```
git clone git@github.com:immera/payment-backend.git
```
So now you have package code inside your repo.

now you need to link it to the local repo instead of downloading from packagist, so to achive that you need to make some changes in package.json

you need to add the following code part in the `composer.json` file.

```json
{
    // ...
    "repositories": {
        "immera/payment-backend": {
            "type": "path",
            "url": "<PATH_TO_REPO>/packages/immera/payment-backend",
            "options": {
                "symlink": true
            }
        }
    }
}
```
Make sure you put it at right place and correct the PATH_TO_REPO

now you only need to reload the package using
```
composer require immera/payment-backend
```
this will load your local package, and now you can make any changes in that package.

once you done with your changes and happy with the new updates, then you can raise PR to actual repo. 

HAPPY CODING !!


