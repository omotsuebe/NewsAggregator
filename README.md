<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## News Aggregator Backend

News aggregator API that pulls articles from various sources and serves them to the frontend application.


### Data Sources

The application integrates with the following APIs to fetch news articles:

1. **NewsAPI**: [Learn more.](https://newsapi.org/docs)
4. **The Guardian**: [Learn more.](https://open-platform.theguardian.com/documentation/)
5. **New York Times**: [Learn more.](https://developer.nytimes.com/docs/articlesearch-product/1/overview).


## Project Setup

### Prerequisites

- Laravel 11
- PHP >= 8.2
- Database: SQLite
- Environment: Ensure your local environment meets [ Laravel's requirements](https://laravel.com/docs/11.x/deployment)

### Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/hezecom/NewsAggregator.git
    cd NewsAggregator
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

3. Copy the [.env.example](http://_vscodecontentref_/0) file to [.env](http://_vscodecontentref_/1) and configure your environment variables:
    ```sh
    cp .env.example .env
    ```

4. Generate an application key:
    ```sh
    php artisan key:generate
    ```

5. Run database migrations:
    ```sh
     php artisan migrate
    ```
### Serving the Application
To serve the application locally, use the following command
   ```sh
    php artisan serve

    The application will be available at http://localhost:8000
   ```

### Running Scheduled Tasks
Fetching and saving articles

- To fetch articles from the configured sources and save them to the database, use:
   ```sh
    php artisan articles:manage
   ```

Scheduling Fetching and saving articles  
- Set up a CRON job to run the schedule:run command every two hours:
   ```sh
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ``` 
[ Laravel's Scheduling](https://laravel.com/docs/11.x/scheduling)

### Running Tests

To run the tests, use the following command:
```sh
php artisan test