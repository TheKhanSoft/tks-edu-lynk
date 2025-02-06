# TKS Edu Lynk

TKS Edu Lynk is a web-based application designed to provide affordable, efficient, and comprehensive solutions for schools and colleges. The platform streamlines office operations by offering modules for student management, fee collection, transport management, and more.

## Features

- Student Management
- Fee Collection
- Transport Management
- Attendance Tracking
- Exam Management
- Staff Management

## Getting Started

### Prerequisites

Before you begin, ensure you have met the following requirements:

- PHP >= 8.0
- Composer
- MySQL or PostgreSQL
- Node.js and npm
- Git

### Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**
    ```sh
    git clone https://github.com/TheKhanSoft/tks-edu-lynk.git
    cd tks-edu-lynk
    ```

2. **Install PHP dependencies:**
    ```sh
    composer install
    ```

3. **Install JavaScript dependencies:**
    ```sh
    npm install
    ```

4. **Copy the `.env.example` file to `.env` and configure your environment variables:**
    ```sh
    cp .env.example .env
    ```

5. **Generate an application key:**
    ```sh
    php artisan key:generate
    ```

6. **Run database migrations and seeders:**
    ```sh
    php artisan migrate --seed
    ```

7. **Build the front-end assets:**
    ```sh
    npm run dev
    ```

8. **Start the development server:**
    ```sh
    php artisan serve
    ```

Your application should now be running on [http://localhost:8000](http://localhost:8000).

### Usage

1. **Access the application:**
   Open your browser and go to [http://localhost:8000](http://localhost:8000).

2. **Login:**
   Use the default credentials to log in:
   *Admins*
   - Email: `superadmin@thekhansoft.com`
   - Password: `SuperAdmin@1234`
  
   -

4. **Explore the features:**
   Navigate through the various modules to explore the features of TKS Edu Lynk.

### Running Tests

To run the test suite, use the following command:

```sh
php artisan test
