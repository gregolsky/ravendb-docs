#!/bin/bash
# Docker build script for Docusaurus documentation site
# This script builds the Docker image using the existing Dockerfile

set -e  # Exit on any error

echo "Building Docker image for Docusaurus documentation site..."
echo

# Set image name and tag
IMAGE_NAME="docusaurus-docs"
IMAGE_TAG="latest"

# Build the Docker image
echo "Building image: ${IMAGE_NAME}:${IMAGE_TAG}"
docker build -f deployment/Dockerfile -t "${IMAGE_NAME}:${IMAGE_TAG}" .

# Check if build was successful
if [ $? -eq 0 ]; then
    echo
    echo "✅ Docker image built successfully!"
    echo
    echo "To run the container:"
    echo "  docker run -p 8080:80 ${IMAGE_NAME}:${IMAGE_TAG}"
    echo
    echo "The site will be available at: http://localhost:8080"
else
    echo
    echo "❌ Docker build failed!"
    exit 1
fi