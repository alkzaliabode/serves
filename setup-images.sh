mkdir -p public/images
echo "Creating no-image placeholder..."

# Download a free-to-use placeholder image if curl is available
if command -v curl &> /dev/null; then
    curl -o public/images/no-image.png https://www.pngitem.com/pimgs/m/254-2549834_404-page-not-found-404-not-found-png.png || echo "Failed to download, creating empty placeholder instead"
else
    echo "curl not found, creating empty placeholder instead"
fi

# If download failed, create a simple placeholder
if [ ! -f public/images/no-image.png ]; then
    echo "Creating a simple placeholder image"
    # This creates a simple 1x1 transparent PNG
    echo -ne '\x89\x50\x4E\x47\x0D\x0A\x1A\x0A\x00\x00\x00\x0D\x49\x48\x44\x52\x00\x00\x00\x01\x00\x00\x00\x01\x08\x06\x00\x00\x00\x1F\x15\xC4\x89\x00\x00\x00\x0A\x49\x44\x41\x54\x78\x9C\x63\x00\x01\x00\x00\x05\x00\x01\x0D\x0A\x2D\xB4\x00\x00\x00\x00\x49\x45\x4E\x44\xAE\x42\x60\x82' > public/images/no-image.png
fi

echo "Placeholder image created at public/images/no-image.png"

# Run the regenerate command
php artisan regenerate-image-reports

echo "Image setup complete!"
