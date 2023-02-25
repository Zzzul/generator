
https://user-images.githubusercontent.com/62506582/200510814-9b2ca922-bd35-4e02-a236-047c4b7b118d.mp4

<p align="center">Laravel starter app and CRUD generator.</p>

<div align="center">

[![All Contributors](https://img.shields.io/github/contributors/Zzzul/generator?style=flat-square)](https://github.com/Zzzul/generator/graphs/contributors)
![GitHub last commit](https://img.shields.io/github/last-commit/Zzzul/generator.svg?style=flat-square)
[![License](https://img.shields.io/github/license/Zzzul/generator.svg?style=flat-square)](LICENSE)
[![Issues](https://img.shields.io/github/issues/Zzzul/generator?style=flat-square)](Issues)
[![Forks](https://img.shields.io/github/forks/Zzzul/generator?style=flat-square)](Forks)
[![Stars](https://img.shields.io/github/stars/Zzzul/generator?style=flat-square)](Stars)

</div>

## Table of Contents
1. [Requirements](#requirements)
2. [What's inside?](#what-inside) 
3. [Features](#features)
4. [Setup](#setup)
5. [Usage](#usage)
6. [License](#license)
7. [Support](#support)

## Requirements
- [PHP ^8.1](https://www.php.net/releases/8.1/en.php)

<h2 id="what-inside">What's inside?</h2>

- [Laravel - ^9.x](https://laravel.com/)
- [Laravel Forify - ^1.x](https://laravel.com/docs/9.x/fortify)
- [Laravel Debugbar - ^3.x](https://github.com/barryvdh/laravel-debugbar)
- [Spatie permission - ^5.x](https://github.com/spatie/laravel-permission)
- [Yajra datatable - ^10.x](https://yajrabox.com/docs/laravel-datatables/master/installation)
- [Intervention Image - ^2.x](https://image.intervention.io/v2)
- [Mazer template - ^2.x](https://github.com/zuramai/mazer/)
- [Generator - ^0.1.x](https://github.com/Zzzul/generator-src/)

## Features
- [x] Authentication ([Laravel Fortify](https://laravel.com/docs/9.x/fortify))
    - Login
    - Register
    - Forgot Password
    - 2FA Authentication
    - Update profile information 
- [x] Roles and permissions ([Spatie Permission](https://spatie.be/docs/laravel-permission/v5/introduction))
- [x] CRUD User
- [x] CRUD Generator
    - Support more than [15 column type migration](https://laravel.com/docs/9.x/migrations#available-column-types), like string, char, date, year, etc.
    - Datatables ([Yajra datatables](https://github.com/yajra/laravel-datatables))
    - BelongsTo relation
    - Model casting
    - Image upload ([Intervention Image](https://image.intervention.io/v2))
    - Support [HTML 5 Input](https://developer.mozilla.org/en-US/docs/Learn/Forms/HTML5_input_types)
    - Request validations supported: 
        - required, in, image, min, max, string, email, number, date, exists, nullable, unique, comfirmed

## Setup
1. Clone or download from [Releases](https://github.com/Zzzul/generator/releases)
```bash
git clone https://github.com/Zzzul/generator.git
```

2. CD into `/generator`
```shell 
cd generator
```

3. Install Laravel dependency
```shell
composer install
```

4. Create copy of ```.env```
```shell
cp .env.example .env
```

5. Generate laravel key
```shell
php artisan key:generate
```

6. Set database name and account in ```.env```
```shell
DB_DATABASE=generator
DB_USERNAME=root
DB_PASSWORD=
```

7.  Run Laravel migrate and seeder
```shell
php artisan migrate --seed
``` 

8. Create the symbolic link
```shell
php artisan storage:link
``` 

9. Start development server
```shell
php artisan serve
``` 

## Usage
Go to ```/generators/create```

Account
- Email: admin@example.com
- Password: password

## License
[MIT License](./LICENSE)

## Support
<a href="https://www.buymeacoffee.com/mzulfahmi" target="_blank">
<img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;">
</a>

Or you can support me at [Ko-fi](https://ko-fi.com/mzulfahmi) or [Saweria](https://saweria.co/zzzul)
