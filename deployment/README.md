# Deployment

This directory contains Docker configuration for deploying the Docusaurus site.

## Building the Docker Image

From the project root directory:

```bash
docker build -f deployment/Dockerfile -t docusaurus-site .
```

## Running the Container

```bash
docker run -p 8080:80 docusaurus-site
```

The site will be available at http://localhost:8080

## Docker Compose (Optional)

You can also create a `docker-compose.yml` file for easier management:

```yaml
version: '3.8'
services:
  docusaurus:
    build:
      context: .
      dockerfile: deployment/Dockerfile
    ports:
      - "8080:80"
    restart: unless-stopped
```

Then run:
```bash
docker-compose up -d
```

## Nginx Configuration

The nginx configuration is stored in `deployment/nginx.conf` and includes:
- Client-side routing support with `try_files` directive for SPA applications
- Static asset caching with 1-year expiration for optimal performance
- Proper MIME type handling for common web assets

To customize the nginx configuration, edit the `deployment/nginx.conf` file.

## Production Considerations

- The nginx configuration includes caching headers for static assets
- Client-side routing is handled with `try_files` directive
- For production, consider:
  - Using a reverse proxy (like Traefik or another nginx instance) for SSL termination
  - Setting up proper logging and monitoring
  - Configuring security headers
  - Modifying the `deployment/nginx.conf` file for advanced configurations