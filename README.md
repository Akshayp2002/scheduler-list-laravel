# Laravel Scheduler Control Center

[![Latest Version on Packagist](https://img.shields.io/packagist/v/akshay/scheduler-list-laravel.svg?style=flat-square)](https://packagist.org/packages/akshay/scheduler-list-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/Akshayp2002/scheduler-list-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/Akshayp2002/scheduler-list-laravel/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/akshay/scheduler-list-laravel.svg?style=flat-square)](https://packagist.org/packages/akshay/scheduler-list-laravel)
[![License](https://img.shields.io/packagist/l/akshay/scheduler-list-laravel.svg?style=flat-square)](LICENSE.md)

A breathtaking, premium, real-time developer dashboard for monitoring, searching, filtering, and manually executing Laravel scheduled tasks in a single click—fully inspired by Laravel Pulse.

---

## ✨ Features

- **💫 Pulse-Style Dashboard**: A premium user interface featuring fluid modern glassmorphism panels, harmonious tailored HSL color schemes, and subtle interactive micro-animations.
- **🌗 Steady Toggle Theme Switcher**: Full Dark and Light theme adaptability with local storage persistence and transition controls.
- **⚡ In-Process Manual Triggering**: Trigger Artisan commands, Closure callbacks, and shell jobs directly from the UI with zero queue delays.
- **📟 Beautiful Built-in Console**: Executes and streams terminal logs in real-time within an interactive overlay (custom-designed in Xcode-dark and DevTools-light styles).
- **🔍 Real-Time Fuzzy Search & Filtering**: Instantly search by command name, expression, or description. Filter tasks by type (Artisan, Callbacks, Shell) with active badge indicators.
- **🏷️ Smart Meta Indicators**: Real-time next run schedules (Carbon countdowns), timezone details, total task count, and task constraints (e.g. *Without Overlapping*, *On One Server*, *In Maintenance*).
- **🎨 Custom Favicon & Logo**: Displays a bespoke console terminal brand logo and a matching self-contained inline SVG favicon.

---

## 🚀 Installation

You can install the package via Composer:

```bash
composer require akshay/scheduler-list-laravel
```

You can publish the configuration file using:

```bash
php artisan vendor:publish --tag="scheduler-list-laravel-config"
```

This will place a simplified and clean configuration file inside `config/scheduler-list.php`.

---

## ⚙️ Configuration

Here is the default configuration file structure inside `config/scheduler-list.php`:

```php
return [
    /*
     * The path/URL where the scheduler dashboard will be accessible.
     */
    'path' => 'schedulers',

    /*
     * The middleware applied to the scheduler dashboard routes.
     * You should restrict this in production (e.g. ['web', 'auth']).
     */
    'middleware' => ['web'],

    /*
     * Whether the scheduler dashboard is enabled.
     */
    'enabled' => true,

    /*
     * Allow developers to run scheduled tasks manually from the dashboard.
     */
    'manual_execution' => true,
];
```

---

## 💡 Usage

1. Open your host application's console router (`routes/console.php` or `Console/Kernel.php`) and register your scheduled tasks:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('inspire')
    ->everyMinute()
    ->description('Displays a random motivational quote.');

Schedule::call(function () {
    echo "Processing database backups...";
})->everyFiveMinutes()->description('Database Backup');
```

2. Make sure your local development server is running:

```bash
php artisan serve
```

3. Visit the dashboard directly in your browser:
👉 **[http://localhost:8000/schedulers](http://localhost:8000/schedulers)** (or your custom `path` configuration).

4. Click **Run** on any task to instantly view logs stream inside the dark/light adaptive overlay console window!

---

## 🧪 Testing

The package includes a comprehensive feature test suite validating routes, manual triggers, standard output streaming, and security blocks:

```bash
composer test
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to open a Pull Request or report bugs in the Issues page.

---

## 📄 License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more details.
