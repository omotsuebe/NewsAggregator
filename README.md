

## News Aggregator Backend

This project is a Laravel application designed to fetches articles from multiple sources using their respective APIs.
 The articles are stored in a local database. Stored articles supports searching, filtering, and respecting user preferences.

### Features

- Retrieve articles from multiple sources (e.g., NewsAPI, The Guardian, New York Times).
- Filter articles by categories, authors, sources, and dates.
- Search functionality with advanced query support.
- Pagination with a maximum limit of 20 articles per page.
- Configurable article fetching limits and API keys.
- Manually fetch articles using the Artisan command.
- Schedule automatic fetching of articles every two hours.
- Continuous Integration (CI): Automated tests run during every pull request or push.
- Instruct Pint to fix code style issues (`./vendor/bin/pint`)

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
### Key Configuration

- **NEWS_API_KEY:** API key for NewsAPI.
- **GUARDIAN_API_KEY:** API key for The Guardian.
- **NEW_YORK_TIMES_API_KEY:** API key for The New York Times.
- **ARTICLE_MAX:** Maximum number of articles fetched per API source in one cycle.

```php
NEWS_API_KEY=enter_key_here
GUARDIAN_API_KEY=enter_key_here
NEW_YORK_TIMES_API_KEY=enter_key_here
ARTICLE_MAX=100
```

### Serving the Application
To serve the application locally, use the following command
   ```sh
    php artisan serve

    The application will be available at http://localhost:8000
   ```

### Running Scheduled Tasks

**Fetching and saving articles**

- To fetch articles from the configured sources and save them to the database, use:
   ```sh
    php artisan articles:manage
   ```

**Scheduling Fetching and saving articles** 
- Set up a CRON job to run the `schedule:run` command every two hours:
   ```sh
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ``` 
[ Learn more about laravel scheduling](https://laravel.com/docs/11.x/scheduling)

### Usage

**Search and Filter**

Use the search and filtering features to retrieve articles based on:

- **Search Queries:** Match titles or descriptions.
- **Filters:** Filter by date, category, source, or author.
- **Pagination:** Navigate through articles with a maximum of 20 articles per page.

**Example API Endpoints**

**Fetch Articles:** 
- `GET /api/v1/articles`

**Optional Query parameters:**

- `search:` Search keywords.
- `category:` Filter by category (e.g `Foreign` | `Sports`  | `Technology`).
- `source:` Filter by source (`News API` | `New York Times` | `Guardian`).
- `author:` Filter by author.
- `from_date:` Start date for filtering (e.g `2024-12-21`).
- `to_date:` End date for filtering (e.g `2024-12-22`)
- `limit:` Number of articles per page (default: 10, max: 20).
- `page:` Current page number (default: 1)

**Sample API Response**
When fetching articles, the API returns a structured JSON response:
```json
{
    "result": true,
    "status": "success",
    "message": "Articles fetched",
    "data": {
        "articles": [
            {
                "id": 1,
                "title": "Sample Article",
                "author": "Author 1",
                "description": "This is a description of the sample article.",
                "url": "https://example.com/sample-article",
                "category": "Technology",
                "source": "Source 1",
                "published_at": "2024-12-21T23:40:56+0000",
                "created_at": "2024-12-21T23:40:56+0000",
                "updated_at": "2024-12-21T23:40:56+0000"
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 10,
            "per_page": 10,
            "total": 100
        }
    }
}
```

- `GET /api/v1/article/{id}`
```json
{
    "result": true,
    "status": "success",
    "message": "Article fetched",
    "data": {
        "articles": {
            "id": 1,
            "title": "Sample Article",
            "author": "Author 1",
            "description": "This is a description of the sample article.",
            "url": "https://example.com/sample-article",
            "category": "Technology",
            "source": "Source 1",
            "published_at": "2024-12-21T23:40:56+0000",
            "created_at": "2024-12-21T23:40:56+0000",
            "updated_at": "2024-12-21T23:40:56+0000"
        }
    }
}

```

### Running Tests

To run the tests, use the following command:
```sh
php artisan test
