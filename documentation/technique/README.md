# home-hub

## Development Environment Initialization

Use the `make install` command to initialize the project.

All the commands mentioned here require connecting to the Docker `app` container.

Create a default user with the command:
```sh
php artisan make:filament-user
```

Connect to the database to manually verify the user's email by adding a verification date.

Ensure that the email address used to log in is defined in the list of authorized users and admins in the `.env` file.

### Clear Caches
```sh
php artisan route:cache
php artisan config:cache
```

### Install npm and Start the Development Server
```sh
npm install
npm run dev
```

### Publish Filament Assets
```sh
php artisan filament:assets
```
