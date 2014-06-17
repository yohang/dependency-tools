Install NPM & Bower dependencies with Composer
==============================================

This simple tools allows you to run `npm install` and/or `bower install` each time you run composer install / update.

*Note: This is Windows-compatible !*

Usage
-----

### Basic usage

Add this lines to your composer.json file (only works with root file) :

```json

{
    "require": {
        "yohang/dependency-tools": "1.1.*"
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

### Advanced usage

If you don't have a global install of bower, you would maybe like to specify a path to bower. If you uses npm to install
bower directly in your project here is an example of how you can configure composer:

```json
{
    "extra": {
        "dependency-tools": {
            "npm": true,
            "bower": {
                "path": "node_modules/.bin/bower"
            }
        }
    }
}
```

