#!/bin/bash
chmod -R 777 storage/ bootstrap/cache/ public/
rm -f storage/framework/sessions/*
rm -f storage/framework/views/*
rm -f storage/framework/cache/*
echo "Permissions fixed!"
