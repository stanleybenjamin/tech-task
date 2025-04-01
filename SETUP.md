## Installation Instructions

1. Install dependencies:
   ```sh
   composer install
   ```

2. Copy the environment file:
   ```sh
   cp .env.example .env
   ```

3. Set up MySQL connection:
   - Open `.env` file and configure your database settings:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

4. Generate application key:
   ```sh
   php artisan key:generate
   ```

5. Ensure Passport keys are generated (important for authentication):
   ```sh
   php artisan passport:keys --force
   ```

## Testing

- To run tests:
  ```sh
  php artisan test
  ```

- To test with Postman:
  - Migrate and seed the database:
    ```sh
    php artisan migrate:fresh --seed
    ```
  - Create Personal Access Client:
    ```sh
    php artisan passport:client --personal
    ```
  - Copy the generated client token and add it to the `.env` file:
    ```env
    PERSONAL_ACCESS_CLIENT_ID=your_client_id
    PERSONAL_ACCESS_CLIENT_SECRET=your_client_secret
    ```
  - Use the following Postman link:
    ```
    [Insert Postman Link Here]
    ```
  
  - **Admin Credentials:**
    ```
    Username: example@email.com
    Password: password
    ```
