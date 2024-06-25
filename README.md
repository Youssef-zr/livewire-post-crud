# Laravel CRUD Application with Livewire, Intervention Image, Laravel Collective, and Toastr

Welcome to the README file for your Laravel CRUD application! This project provides a simple CRUD interface
to manage posts, utilizing Laravel framework along with Livewire,
Intervention Image, Laravel Collective, and Toastr.

## Description

This project allows users to perform CRUD operations on posts without requiring user registration.
Intervention Image is used for handling image uploads, Laravel Collective for form handling, and Toastr for displaying notifications.

## Features

-   Create, Read, Update, and Delete posts.
-   Image upload and display using Intervention Image.
-   Form handling with Laravel Collective.
-   Toastr notifications for user feedback.

## Requirements

-   PHP >= 8.1
-   Composer
-   Laravel >= 10.x
-   Node.js (for npm packages if applicable)

## Installation

To run this project locally, follow these steps:

1. Clone the repository:

    ```bash
    git clone <repository-url>

    ```

2. Navigate into the project directory:

````bash
 cd project-directory

3. Install PHP dependencies with Composer:

```bash
 composer install

3. Run database migrations:

```bash
 php artisan migrate

4. Seed the database with sample data:
  ```bash
  php artisan db:seed

4. run project:
  ```bash
  php artisan serve

5. open this link:
 http://127.0.0.1:8000/posts
````
