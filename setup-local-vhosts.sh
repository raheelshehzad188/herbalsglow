#!/bin/bash
set -e
HOSTS_BLOCK='
# Herbals Glow local stores
127.0.0.1 classic.herbalsglow.test
127.0.0.1 wellness.herbalsglow.test
127.0.0.1 shopus.herbalsglow.test
'
if ! grep -q 'classic.herbalsglow.test' /etc/hosts; then
  echo "$HOSTS_BLOCK" >> /etc/hosts
fi
/Applications/XAMPP/xamppfiles/bin/apachectl graceful
echo "Hosts + Apache reload done."
