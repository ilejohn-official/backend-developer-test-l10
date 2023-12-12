# Iphone Photography Service

## Table of contents

- [General Info](#general-info)
- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)

## General Info

This project unlocks achievements and badges based on user activity.

## Requirements

- [php ^8.1](https://www.php.net/ "PHP")

## Setup

- Clone the project and navigate to it's root path and install the required dependency packages using the below commands on the terminal/command line interface.

  ```bash
  git clone https://github.com/ilejohn-official/backend-developer-test-l10
  cd backend-developer-test-l10
  ```

  ```
  composer install
  ```

- Copy and paste the content of the .env.example file into a new file named .env in the same directory as the former and set it's  
  values based on your environment's configuration.

- Generate Application Key

  ```
  php artisan key:generate
  ```
- Run Migration

  ```
  php artisan migrate
  ```
- Run seeder

  ```
  php artisan db:seed
  ```

## Usage

- To run local server

  ```
  php artisan serve
  ```

- To run tests

  Copy .env.example into .env.testing and set `APP_ENV` as "testing". Update the database details to match your local setup then run

  ```
  php artisan test --env=testing
  ```
