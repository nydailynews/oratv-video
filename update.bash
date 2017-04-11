#!/bin/bash
# Pull in any new feed items for the video channels we're publishing.
# Pass in arguments if you want to scp the channel CSV to prod, like so:
# ./update.bash --prod server.name /path/to/app/dir
while [ "$1" != "" ]; do
    case $1 in
        -p | --prod ) shift
            PROD=$1
            PROD_PATH=$2
            ;;
    esac
    shift
done

if [ -e .source.bash ]; then
    source .source.bash
fi

declare -a FEEDS=('http://feeds.ora.tv/partner/thedailynewslarge/worldware-episodes.mrss')
declare -a CHANNELS=('ora-mike-rogers-world-war-e')
# Arrays are 0-indexed, but the length of arrays lives in a 1-indexed world.
ITEMS=`expr ${#CHANNELS[@]} - 1`

for i in $(seq 0 $ITEMS); do 
    NEW_CSV="www/new-${CHANNELS[$i]}.csv"
    CHANNEL_CSV="www/channel-${CHANNELS[$i]}.csv"
    head -n 1 $CHANNEL_CSV > $NEW_CSV
    python recentfeed.py ${FEEDS[$i]} --output csv --days 3 >> $NEW_CSV
    COUNT=`cat $NEW_CSV | wc -l`
    if [ $COUNT -gt 1 ]; then
        echo $COUNT
        python addtocsv.py $NEW_CSV $CHANNEL_CSV
    fi
done

# Move the CSV to prod, if necessary
if [ ! -z $PROD ]; then
    scp $CHANNEL_CSV $PROD:$PROD_PATH/
fi
