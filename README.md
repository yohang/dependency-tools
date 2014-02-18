Install NPM & Bower dependencies with Composer
==============================================

This simple tools allows you to run `npm install` and/or `bower install` each time you run composer install / update.

Usage
-----

Add this lines to your composer.json file (only works with root file) :

```json

{
    "require": {
        "yohang/dependency-tools": "1.0.*"
    },
    "scripts": {
        "post-install-cmd": [
            "Yohang\\DependencyTools::installDeps"
        ],
        "post-update-cmd": [
            "Yohang\\DependencyTools::updateDeps"
        ]
    },
    "extra": {
        "dependency-tools": {
            "npm": true,
            "bower": true
        }
    }
}

```

And that's all, your NPM and Bower dependencies will be installed just after your Composer dependencies.
