#!/usr/bin/env bash
now=$(date)
PARAMS_1=$1
CRAWLER_NAME="lovetv-vina/public/index.php VasCloud Commands CDRProxy $PARAMS_1"
CRAWLER_COMMAND="/var/www/html/lovetv-vina/public/index.php VasCloud Commands CDRProxy $PARAMS_1"

if [ $(ps -efa | grep -v grep | grep "$CRAWLER_NAME" -c) -gt 0 ]; then
  echo "Current has one Process running $CRAWLER_NAME at PHP command: $CRAWLER_COMMAND ..."
else
  echo "Start Send Worker SyncCDR $PARAMS_1 at {$now} -> $CRAWLER_COMMAND .............."
  php /var/www/html/lovetv-vina/public/index.php VasCloud Commands CDRProxy "$PARAMS_1"
fi
