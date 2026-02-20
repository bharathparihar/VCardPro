# Deploying Laravel to Render.com with Docker (Free Forever)

This guide will walk you through hosting your Laravel application on Render.com using Docker for free. To keep it "free forever", we will use Render's Free Web Service tier and a free external PostgreSQL database, as Render's native free database is limited to 90 days.

## Prerequisites

1.  **GitHub or GitLab Account**: To store your code.
2.  **Render.com Account**: To host the application.
3.  **Neon.tech Account** (or Supabase): For a free, persistent PostgreSQL database.

## Step 1: Prepare Your Application

We have already created the necessary Docker configuration files for you:
*   `Dockerfile`: Instructions to build your Laravel app image.
*   `.dockerignore`: Files to exclude from the image.
*   `render.yaml`: Configuration for Render.
*   `docker-entrypoint.sh`: Startup script to run migrations.

### 1.1 Verify Configuration
Ensure your `composer.json` requirements match the `Dockerfile` (PHP 8.2). Our setup uses PHP 8.2 which is compatible with Laravel 10+.

### 1.2 Push to GitHub/GitLab
Create a new repository and push your code.

```bash
git init
git add .
git commit -m "Initial commit with Docker config"
# Add your remote origin
# git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

## Step 2: Set Project to Production Mode

In your `render.yaml`, environment variables are set for production.

**Note:** You need to generate an `APP_KEY`. Run this command in your local terminal and save the output:
```bash
php artisan key:generate --show
```
You will need this key in Step 4.

## Step 3: Set Up a Free Database

Since Render's free database expires after 90 days, use **Neon.tech** for a permanently free PostgreSQL database.

1.  Sign up at [Neon.tech](https://neon.tech).
2.  Create a new project.
3.  Copy the **Connection String** (Postgres URL). It looks like: `postgres://user:password@ep-xyz.region.aws.neon.tech/neondb?sslmode=require`.

## Step 4: Deploy on Render

1.  Log in to the [Render Dashboard](https://dashboard.render.com/).
2.  Click **New +** and select **Blueprints**.
3.  Connect your GitHub/GitLab account and select your repository.
4.  Render will detect the `render.yaml` file.
5.  It will prompt you for the `APP_KEY` and Database details (Environment Variables).

### Configure Environment Variables
Fill in the values in the Render dashboard prompt:

*   **APP_KEY**: Paste the key you generated in Step 2 (e.g., `base64:xyz...`).
*   **DB_CONNECTION**: `pgsql` (Default is set in `render.yaml`).
*   **DB_HOST**: The host from your Neon connection string (e.g., `ep-xyz.aws.neon.tech`).
*   **DB_PORT**: `5432`.
*   **DB_DATABASE**: The database name (e.g., `neondb`).
*   **DB_USERNAME**: The user from connection string.
*   **DB_PASSWORD**: The password from connection string.
*   **DB_SSLMODE**: `require` (Important for external DBs).

**Note:** If `render.yaml` doesn't automatically ask for all of these, you can add them manually in the **Environment** tab after the service is created.

6.  Click **Apply**.

## Step 5: Finalize Deployment

Render will now:
1.  Clone your repo.
2.  Build the Docker image using `Dockerfile`.
3.  Deploy the service.
4.  Run `docker-entrypoint.sh` which executes `php artisan migrate --force` to set up your database tables.

Once valid, your app will be live at `https://vcards-xxxx.onrender.com`.

## Important Notes on Free Tier
*   **Spin Down:** The free Web Service on Render spins down after 15 minutes of inactivity. The first request after a while will take ~30-60 seconds to load. This is normal for the free tier.
*   **Database:** By using Neon.tech, your data is safe and won't be deleted after 90 days.
*   **Storage:** The filesystem is **ephemeral**. Any files uploaded to `storage/` (like user avatars) will be lost when the app restarts/redeploys.
    *   **Solution:** Use an external storage service like **AWS S3** or **Cloudinary** for file uploads. You can configure this in `.env` (Environment Variables on Render).

## Troubleshooting
*   **500 Error**: Check the **Logs** tab in Render. It usually means a missing environment variable (like `APP_KEY` or DB credentials).
*   **Database Connection Failed**: Ensure `DB_SSLMODE` is set to `require` if using Neon.
