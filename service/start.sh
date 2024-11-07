php cli.php orm:schema-tool:update -f
if [ $? -ne 0 ]; then
    exit 61
fi
exec php-fpm -F -R