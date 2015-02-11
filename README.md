cms
===

[![Build Status](https://travis-ci.org/subbly/cms.svg)](https://travis-ci.org/subbly/cms)
[![Total Downloads](https://poser.pugx.org/subbly/cms/downloads.svg)](https://packagist.org/packages/subbly/cms)
[![Latest Stable Version](https://poser.pugx.org/subbly/cms/v/stable.svg)](https://packagist.org/packages/subbly/cms)
[![Latest Unstable Version](https://poser.pugx.org/subbly/cms/v/unstable.svg)](https://packagist.org/packages/subbly/cms)
[![License](https://poser.pugx.org/subbly/cms/license.svg)](https://packagist.org/packages/subbly/cms)


## Install

    $ git clone https://github.com/subbly/cms.git
    $ composer install

## Setup database

Copy `app/config/database.php` into `app/config/locale/database.php` then edit it with your DB settings.
You can now import MySQL's schemas.

    $ php artisan migrate --package=cartalyst/sentry
    $ php artisan migrate --path vendor/subbly/framework/src/migrations

Done!

Comming soon, dataseeders.
