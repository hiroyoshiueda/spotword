#!/bin/sh

BATCH_DIR=/home/users/0/main.jp-e-sora/apps/spotword/batch

cd $BATCH_DIR

/usr/local/php5.2/bin/php $BATCH_DIR/batch.php $1

exit 0
