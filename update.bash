#!/bin/bash
# Pull in any new feed items for the video channels we're publishing.

declare -a FEEDS=('http://feeds.ora.tv/partner/thedailynewslarge/worldware-episodes.mrss')
declare -a CHANNELS=('ora-mike-rogers-world-war-e')
# Arrays are 0-indexed, but the length of arrays lives in a 1-indexed world.
ITEMS=`expr ${#CHANNELS[@]} - 1`

for i in $(seq 0 $ITEMS); do 
    NEW_CSV="www/new-${CHANNELS[$i]}.csv"
    CHANNEL_CSV="www/channel-${CHANNELS[$i]}.csv"
    python recentfeed.py ${FEEDS[$i]} --output csv --days 2 > $NEW_CSV
    python addtocsv.py $NEW_CSV $CHANNEL_CSV
done
