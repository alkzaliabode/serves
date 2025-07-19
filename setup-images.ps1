# Create the images directory if it doesn't exist
New-Item -Path "public\images" -ItemType Directory -Force | Out-Null
Write-Output "Creating no-image placeholder..."

# Function to download image
function DownloadImage {
    try {
        $webClient = New-Object System.Net.WebClient
        $webClient.DownloadFile("https://www.pngitem.com/pimgs/m/254-2549834_404-page-not-found-404-not-found-png.png", "public\images\no-image.png")
        return $true
    }
    catch {
        Write-Output "Failed to download image: $_"
        return $false
    }
}

# Try to download the image
$downloaded = DownloadImage

# If download failed, create a simple placeholder
if (-not $downloaded) {
    Write-Output "Creating a simple placeholder image"
    
    # Create a small text file as placeholder (since we can't easily create a proper PNG in PowerShell)
    Set-Content -Path "public\images\no-image.png" -Value "PLACEHOLDER IMAGE - Replace with a real image file"
    
    Write-Output "Created text placeholder. Please replace with a proper image file."
}
else {
    Write-Output "Placeholder image downloaded to public\images\no-image.png"
}

# Run the regenerate command
Write-Output "Regenerating image reports..."
php artisan regenerate-image-reports

Write-Output "Image setup complete!"
