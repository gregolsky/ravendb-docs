@echo off
REM Docker build script for Docusaurus documentation site
REM This script builds the Docker image using the existing Dockerfile

echo Building Docker image for Docusaurus documentation site...
echo.

REM Set image name and tag
set IMAGE_NAME=docusaurus-docs
set IMAGE_TAG=latest

REM Build the Docker image
echo Building image: %IMAGE_NAME%:%IMAGE_TAG%
docker build -f deployment/Dockerfile -t %IMAGE_NAME%:%IMAGE_TAG% .

REM Check if build was successful
if %ERRORLEVEL% EQU 0 (
    echo.
    echo ✅ Docker image built successfully!
    echo.
    echo To run the container:
    echo   docker run -p 8080:80 %IMAGE_NAME%:%IMAGE_TAG%
    echo.
    echo The site will be available at: http://localhost:8080
) else (
    echo.
    echo ❌ Docker build failed!
    exit /b 1
)

pause