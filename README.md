Back End Developer Test - Product Data Management
=================================================

Thank you for the opportunity to take this test.

Task Overview
-------------

### Original Task

## Back End Developer Test

This test is designed to test your overall code and architectural skills.

There is a dummy API already configured which you can access at `/product-data` 

You should not have to change this endpoint or its controller.

You should demonstrate how you would consume the data from the API, store it and disseminate that data.

Although not strictly represented in the data, you can assume that there will be many variants per SKU.

## Todo
1. Design an adequate table structure for the data supplied.
2. Write code which will store the data from the API into your table structure.
3. Create an endpoint that will display 20 SKUs including their child variants.
4. You should only return an item if the item is available to buy.
5. Only return Box Quantity, Width, Height and Length if the user is logged in.

This is not an exhaustive list of requirements. This is your opportunity to code to the best of your ability.

## Installation
* Clone the repo into your own account
* Composer install 
* Run `php artisan storage:link` so that you have access to the storage folder (note NOT store:link).
* Perform the tasks as required.
* Push the work to your github account
* Provide a link to the repo to PW



Project Breakdown
-----------------

### Interpretation of the Task

1.  **Table Structure:**

    -   Design the database schema for storing products, SKUs, and variants.

2.  **Data Storage:**

    -   Create a command that fetches the product data from a dummy API and stores it in the database.

3.  **Display Endpoint:**

    -   Implement an endpoint that returns SKUs with variants, filtered based on availability.

4.  **Conditional Fields:**

    -   Ensure that only logged-in users can see specific fields (Box Quantity, Width, Height, Length).

5.  **Security:**

    -   Implement XSS protection using middleware.

### Steps and Analysis

-   **Database Design:** We defined three main models: Product, SKU, and Variant, with necessary relationships (one-to-many).
-   **API Consumption:** We utilized the `Http::retry()` method for resilient API calls.
-   **Conditional Data:** Implemented logic to show specific fields only if the user is authenticated.
-   **Error Handling:** Centralized error reporting using custom services and integrated Sentry for logging errors.
-   **XSS Protection:** Middleware from `protonemedia/laravel-xss-protection` was integrated to protect inputs from cross-site scripting.

Project Structure
-----------------

Below is the file structure, highlighting key files added or modified for this task.


app/
├── Console/
│ └── Commands/
│ └── FetchProductData.php
├── Http/
│ ├── Controllers/
│ │ └── ProductDataController.php
│ ├── Resources/
│ │ ├── SkuResource.php
│ │ └── VariantResource.php
├── Jobs/
│ └── ProcessProductData.php
├── Models/
│ ├── Product.php
│ ├── Sku.php
│ ├── User.php
│ └── Variant.php
├── Providers/
│ ├── AppServiceProvider.php
│ └── TelescopeServiceProvider.php
└── Services/
├── ErrorHandlingService.php
└── ProductDataService.php

bootstrap/
├── app.php
├── providers.php

routes/
└── web.php
└── api.php

tests/
├── Feature/
│   ├── FetchProductDataCommandTest.php
│   ├── QueueTest.php
│   ├── ProcessProductDataRouteTest.php
│   ├── SmokeTest.php
└── Unit/
    ├── ProductDataServiceTest.php
    ├── ProcessProductDataTest.php
    └── DiscountCalculationTest.php



database/
├── factories/
│ └── UserFactory.php
├── migrations/
│ └── ...
└── seeders/
└── DatabaseSeeder.php

storage/
└── app/
└── public/
└── mock_data.json


**Install Dependencies:**

Additional Composer Packages
----------------------------

-   **protonemedia/laravel-xss-protection**
    -   Provides middleware to sanitize input data against XSS attacks.
-   **laravel/telescope**
    -   Used for debugging and monitoring application performance and logs.
-   **sentry/sentry-laravel**
    -   Used to capture and report errors during the application's runtime.

### Explanation of Additional Packages

-   **protonemedia/laravel-xss-protection:** This package is added to ensure the application is protected against XSS vulnerabilities by cleaning input data automatically.
-   **laravel/telescope:** Added to aid in debugging by tracking requests, exceptions, and queries during the development phase.
-   **sentry/sentry-laravel:** Integrated for error reporting, allowing exceptions and failures to be tracked and alerted.

Explanation of Key Files
------------------------

-   **`FetchProductData.php` (Command):** Fetches product data from the API and stores it in the database.
-   **`ProductDataController.php`:** Controls the logic for retrieving and displaying product data and SKUs.
-   **`XssCleanInput.php` (Middleware):** Sanitizes incoming input to prevent XSS attacks.
-   **`ProcessProductData.php` (Job):** Handles processing and saving of product data using Laravel's queue system.
-   **`SkuResource.php` & `VariantResource.php`:** Transforms SKU and variant data into structured JSON responses.
-   **`ProductDataService.php`:** Service that handles fetching and storing product data with validation and error handling.
-   **`ErrorHandlingService.php`:** Centralized error handling and reporting service that integrates with Sentry.

Minimum Requirements
--------------------

-   **PHP:** 8.2 or higher
-   **Laravel:** 11.x
-   **Composer:** Ensure that all dependencies are installed using Composer.

Running the Application
-----------------------

### Step-by-Step Guide

1.  **Clone the Repository:** Clone the repository to your local machine.

2.  **Install Dependencies:** Run `composer install` to install all the necessary packages.

3.  **Set Up Environment:** Copy `.env.example` to `.env` and `.env.testing`  and configure your environment variables.
`copy .env.example .env`
`copy .env.example .env.testing`
`php artisan key:generate`

`cp .env.example .env`
`cp .env.example .env.testing`
`php artisan key:generate --env=testing`

4.  **Configure the Database:** Update your `.env` file with database credentials.

5.  **Run Migrations:** Execute the migrations to set up your database tables. ie `php artisan migrate` or for testing `php artisan migrate --env=testing`


6.  **Seed the database if necessary:** You may need to seed the database with initial data.

7.  **Run Fetch Command:** Making sure that you are running  php artisan serve in another cmd windowd, Use the command `php artisan fetch:product-data` or for testing `php artisan fetch:product-data --env=testing` to fetch product data from the API and store it in the database. Once completed, it will log a message indicating the data has been fetched and stored successfully.

### Viewing the Results

You can view the results by accessing the following URLs:

-   **Product Data:** `http://127.0.0.1:8000/product-data`\
    This URL will return the raw product data from the mock JSON file.

-   **SKUs:** `http://127.0.0.1:8000/skus`\
    This URL will return the list of SKUs, including their variants. If you are authenticated, it will also include Box Quantity, Width, Height, and Length.

### Expected Output

-   **Product Data:** JSON response with product data fetched from the mock JSON file.
-   **SKUs:** JSON response with up to 20 SKUs that are available for sale, including their variants. Dimensions will be included only if the user is authenticated.

### Queue Testing

In addition to the standard unit and feature tests, this project includes tests specifically designed to verify that jobs are correctly dispatched to the queue. The QueueTest class ensures that when a particular action occurs, the corresponding job is placed on the queue for asynchronous processing.

These tests are crucial for confirming the integration between your application's business logic and the Laravel queue system. They simulate job dispatching without executing the jobs, thus ensuring that the queue system is utilized as expected.

Important Note: While these tests confirm that jobs are dispatched, they do not validate the actual execution of these jobs in a production environment. In production, jobs are processed by the queue worker, which should be monitored and tested separately to ensure it functions correctly under real-world conditions.

Make sure to run these queue tests regularly, especially when modifying code that interacts with the queue, to prevent any disruptions in job dispatching.

### Testing

* * * * *

### Testing Environment Setup

1.  **Set up the testing database**: Ensure you configure your `.env.testing` file with the appropriate database connection details for testing purposes. This will isolate test data and ensure it doesn't affect your development or production databases.

2.  **Running the tests**: You can run all the tests using PHPUnit or the Laravel Artisan command:

`php artisan test`

1.  This will execute all tests within the `tests` directory and report on their success or failure.

### Test Coverage

1.  **`FetchProductDataCommandTest.php`** (`tests/Feature/FetchProductDataCommandTest.php`):
    -   Tests the command responsible for fetching and storing product data from an external API.
2.  **`QueueTest.php`** (`tests/Feature/QueueTest.php`):
    -   Ensures that the `ProcessProductData` job is correctly dispatched to the queue when triggered.
3.  **`ProductDataServiceTest.php`** (`tests/Unit/ProductDataServiceTest.php`):
    -   Unit tests for the `ProductDataService`, focusing on its ability to fetch, validate, and store product data.
4.  **`ProcessProductDataTest.php`** (`tests/Unit/ProcessProductDataTest.php`):
    -   Validates the functionality of the `ProcessProductData` job, ensuring it correctly processes and stores product data in the database.
5.  **`ProcessProductDataRouteTest.php`** (`tests/Feature/ProcessProductDataRouteTest.php`):
    -   Tests the `/api/process-product-data` API endpoint, verifying that it correctly dispatches the `ProcessProductData` job.
6.  **`PHPUnit Tests`**:
    -   Includes unit and feature tests that cover various components of the application, ensuring that they work as expected in isolation and in combination.
7.  **`SmokeTest.php`** (`tests/Feature/SmokeTest.php`):
    -   Contains smoke tests that quickly verify the core functionalities of the appli
    cation, including accessibility of the home page, product data endpoint, and SKU endpoint, as well as the `/api/process-product-data` route.
8.  **`SecurityTest.php`** (`tests/Feature/SecurityTest.php`):
    -   Ensures that user input is properly sanitized to prevent XSS attacks and verifies other security measures.
9.   **`DiscountCalculationTest.php`** (`tests/Unit/DiscountCalculationTest.php`):
    -   Tests the discount calculation logic for the `Product` model to ensure that the discount is applied correctly to the product's price.


### Additional Tests

    -   **Endpoint Tests**: You should also include tests for the `/product-data` and `/skus` endpoints:
    -   **Authentication**: Ensure that tests cover authenticated vs. unauthenticated requests to `/skus`.
    -   **Data Integrity**: Verify that these endpoints return the correct data structure and content.
    -   **Smoke Tests**: Perform basic smoke tests to verify the accessibility and functionality of key routes, such as the home page, `/product-data`, `/skus`, and `/api/process-product-data`.
    -   **PHPUnit Tests**: Incorporate comprehensive unit and feature tests using PHPUnit to ensure all components work as expected in isolation and integration.
    -   **Authorization**: Validate that only authorized users can access certain routes or perform specific actions.
    -   **Discount Calculation:** Ensure that the discount calculation for products is accurate by testing different discount percentages against known expected outcomes.

These additions will make sure that the `DiscountCalculationTest` is documented as part of your test suite, providing an overview of what the test covers and its importance in the overall project.


### Continuous Integration

To maintain code quality, consider setting up Continuous Integration (CI) tools like GitHub Actions, CircleCI, or Travis CI to automatically run your test suite on every push or pull request. This ensures that your code remains robust and bug-free.


### 404 Route
---------

A fallback route has been added to handle any undefined routes, returning a JSON response with a 404 error code.

### **DB Indexing Addition**
---------

"To optimize performance, especially when searching using SKUs, indexing has been added to the relevant database columns. This improvement enhances the speed and efficiency of queries, which is particularly beneficial when dealing with large datasets."

### Future Enhancements

* * * * *

-   **API Integration:** Implementing a fully functional API for data retrieval instead of relying on mock data.
-   **Enhanced Security:** Additional security measures, such as more robust validation and input sanitation mechanisms.
-   **Improved Performance:** Utilize caching strategies for frequent API requests and database queries.
-   **User Roles and Permissions:** Implement role-based access control for more granular authorization.
-   **Advanced Testing:** Increase test coverage, particularly for edge cases and failure scenarios.
-   **User Authentication:** Integrating user authentication, such as with Laravel Sanctum, could add a robust layer of security, especially for API token management and user authentication.
-   **Database Indexing:** The recently added database indexing sets the stage for implementing advanced search features, which could be developed to leverage these optimizations fully.
-   **Additonal testing such as secrurity testing ie XSS, CSRF or Authorization Test
