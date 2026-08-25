#!/bin/bash
set -e
for host in classic.herbalsglow.test wellness.herbalsglow.test shopus.herbalsglow.test platform.herbalsglow.test; do
  if ! grep -q "$host" /etc/hosts; then
    echo "127.0.0.1 $host" >> /etc/hosts
  fi
done
/Applications/XAMPP/xamppfiles/bin/apachectl graceful
echo "Hosts + Apache reload done."
