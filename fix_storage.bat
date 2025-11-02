@echo off
echo Fixing storage issues...

REM Create products directory in storage/app/public if it doesn't exist
if not exist "storage\app\public\products" (
    mkdir "storage\app\public\products"
    echo Created products directory in storage/app/public
)

REM Remove existing storage link if it exists
if exist "public\storage" (
    rmdir "public\storage" /s /q
    echo Removed existing storage link
)

REM Create symbolic link from public/storage to storage/app/public
mklink /D "public\storage" "%cd%\storage\app\public"
if %errorlevel% == 0 (
    echo Created symbolic link: public\storage -> storage\app\public
) else (
    echo Failed to create symbolic link. Please run as Administrator.
)

echo.
echo Testing storage link...
if exist "public\storage\products" (
    echo ✅ Storage link is working correctly!
) else (
    echo ❌ Storage link is not working properly.
)

echo.
echo Done! You can now upload product images.
pause