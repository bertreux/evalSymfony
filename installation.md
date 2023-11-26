# Installation Guide

Follow these steps to set up and run the Symfony project.

## Prerequisites

- PHP (>= 8.1)
- Composer
- Symfony CLI
- MySQL or another database system

## Installation

1**Install Dependencies:**

    ```bash
    composer install
    ```

2**Install Dependencies:**

   Make sure to configure your database connection in the `.env` file before running the fixtures.

3**Load Fixtures:**

    ```bash
    php bin/console doctrine:fixtures:load
    ```

   This command will populate the database with sample data.

4**Start the Symfony Server:**

    ```bash
    symfony server:start
    ```

   This will start the Symfony development server. Visit `http://127.0.0.1:8000` in your browser to access the application.

5**Login Credentials:**

   Use the following credentials to log in:

    - **Email:** admin@gmail.com
    - **Password:** eval