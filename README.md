
<h1 align="center">Generator</h1>

https://user-images.githubusercontent.com/62506582/162600284-aa8fe74e-5dca-4d8d-a7bd-b4f907d45958.mp4


<p align="center">Laravel starter app and CRUD generator.</p>

<div align="center">

[![All Contributors](https://img.shields.io/github/contributors/Zzzul/generator?style=flat-square)](https://github.com/Zzzul/generator/graphs/contributors)
![GitHub last commit](https://img.shields.io/github/last-commit/Zzzul/generator.svg?style=flat-square)
[![License](https://img.shields.io/github/license/Zzzul/generator.svg?style=flat-square)](LICENSE)
[![Issues](https://img.shields.io/github/issues/Zzzul/generator?style=flat-square)](Issues)
[![Forks](https://img.shields.io/github/forks/Zzzul/generator?style=flat-square)](Forks)
[![Stars](https://img.shields.io/github/stars/Zzzul/generator?style=flat-square)](Stars)

</div>

## What inside?
- Laravel ^9.x - [Laravel 9](https://laravel.com/docs/9.x)
- PHP ^8.1 - [PHP 8,1](https://www.php.net/releases/8.1/en.php)
- Mazer - [Mazer Admin](https://github.com/zuramai/mazer/)

## Installation
1. Clone or download this repository
```shell
git clone https://github.com/Zzzul/generator
```

Or download from [releases](https://github.com/Zzzul/generator/releases)

2. Install laravel dependency
```sh
composer install
```

3. Create copy of ```.env```
```sh
cp .env.example .env
```

4. Generate laravel key
```sh
php artisan key:generate
```

5. Set database name and account in ```.env```
```sh
DB_DATABASE=generator
DB_USERNAME=root
DB_PASSWORD=
```

6.  Laravel migrate and seeder
```sh
php artisan migrate --seed
``` 

## Usage
Login
- Email: admin@example.com
- Password: password

Then go to ```/generators/create```


## Example
Below are some codes that generate by the generator

<img width="960" alt="controller" src="https://user-images.githubusercontent.com/62506582/162600665-00405a4d-00e2-4c30-88da-74a2f447946a.PNG">
<img width="960" alt="model" src="https://user-images.githubusercontent.com/62506582/162600670-d8bd61a4-cc73-4f8b-8e4d-dabd353ff48d.PNG">
<img width="960" alt="migration" src="https://user-images.githubusercontent.com/62506582/162600667-9686c556-f5b3-4067-bf58-f6f295d360c6.PNG">
<img width="960" alt="blade" src="https://user-images.githubusercontent.com/62506582/162600663-5c26a238-633a-4c14-8bb0-acc6d917d221.PNG">

*note: some code may not format perfectly, but you can format the code manually or using a formatter.


## License
[MIT License](./LICENSE)

## Donation
<a href="
https://www.buymeacoffee.com/mzulfahmi" target="_blank">
<img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;">
</a>

Or you can support me at [Ko-fi](https://ko-fi.com/mzulfahmi) or [Saweria](https://saweria.co/zzzul)
