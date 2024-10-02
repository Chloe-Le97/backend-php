# Alko Product Database

## Overview

This application fetches data from an external Excel file provided by Alko (the Finnish alcohol seller) and stores it in a MySQL database. It allows users to manage products via a frontend interface, with buttons to adjust the order amount of each product. The app dynamically updates the data using AJAX and ensures that changes are reflected in both the database and the frontend interface.

## Features

- **Fetch Alko Data**: Imports product data from an external Excel file from [Alko website](https://www.alko.fi/valikoimat-ja-hinnasto/hinnasto).
- **Database Management**: Manages product data in a MySQL database using PHP and PDO.
- **Frontend Interface**: View and manage product orders via a web interface with real-time updates.
- **AJAX Integration**: Allows for seamless product updates without page reloads.
- **Dockerized Environment**: The app runs inside Docker containers, ensuring an isolated and consistent environment.

## Technologies

- **PHP** (Backend)
- **MySQL** (Database)
- **PDO** (Database interaction)
- **JavaScript** (Frontend with jQuery & AJAX)
- **Docker** (Containerization)
- **Composer** (Dependency Management)
- **Shuchkin\SimpleXLSX** (Excel parsing)

## Prerequisites

- Docker & Docker Compose installed on your machine.
- PHP 8.0+
- Composer (PHP package manager)

## Setup

### 1. Clone the repository
```bash
git clone https://github.com/Chloe-Le97/backend-test.git
cd backend-test

### 2. Set Environment Variables
Create a `.env` file in the root directory and populate it with the following:

```env
MYSQL_ROOT_PASSWORD=password
MYSQL_DATABASE=my_database
MYSQL_USER=user
MYSQL_PASSWORD=user_password

### 3. Build and run Docker containers 
```bash
docker-compose up --no-deps --build

or
```bash
docker compose up --no-deps --build

### 4. Install PHP dependencies
```bash
composer install

### 5. Run PHP Script via Command Line (CLI)
```bash 
php inital_data.php
