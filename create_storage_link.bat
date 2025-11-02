@echo off
echo Creating storage link...

REM Create products directory in storage/app/public if it doesn't exist
if not exist "storage\app\public\products" (
    mkdir "storage\app\public\products"
    echo Created products directory in storage/app/public
)

REM Create symbolic link from public/storage to storage/app/public
if not exist "public\storage" (
    mklink /D "public\storage" "storage\app\public"
    echo Created symbolic link: public\storage -> storage\app\public
) else (
    echo Symbolic link already exists
)

echo Done!
pause