#!/bin/bash
set -e
if [ "$(id -u)" -ne 0 ]; then
  echo "Run with sudo so /etc/hosts and Apache can be updated:"
  echo "  sudo bash $0"
  exit 1
fi
for host in classic.herbalsglow.test wellness.herbalsglow.test shopus.herbalsglow.test platform.herbalsglow.test; do
  if ! grep -q "$host" /etc/hosts; then
    echo "127.0.0.1 $host" >> /etc/hosts
  fi
done
/Applications/XAMPP/xamppfiles/bin/apachectl graceful
echo "Hosts + Apache reload done."
