#!/bin/bash

# Radio Paradise metadata
# status-radioparadise.sh FILENAME
dirtmp=/srv/http/data/shm/
name=$( basename "$1" )
case ${name/-*} in
	flacm )  id=0;;
	mellow ) id=1;;
	rock )   id=2;;
	world )  id=3;;
esac

readarray -t metadata <<< $( curl -sL \
	https://api.radioparadise.com/api/now_playing?chan=$id \
	| jq -r .artist,.title,.album,.cover )
artist=${metadata[0]}
title=${metadata[1]}
album=${metadata[2]}
url=${metadata[3]}
name=$( echo $artist$title | tr -d ' "`?/#&'"'" )
coverfile=$dirtmp/online-$name.jpg
[[ ! -e $coverfile ]] && rm -f $dirtmp/online-*
[[ -n $url ]] && curl -s $url -o $coverfile
[[ -e $coverfile ]] && coverart=/data/shm/online-$name.$( date +%s ).jpg
artist=$( echo $artist | sed 's/"/\\"/g; s/null//' )
title=$( echo $title | sed 's/"/\\"/g; s/null//' )
album=$( echo $album | sed 's/"/\\"/g; s/null//' )
data='{"Artist":"'$artist'", "Title":"'$title'", "Album": "'$album'", "coverart": "'$coverart'", "radio": 1}'
curl -s -X POST http://127.0.0.1/pub?id=mpdplayer -d "$data"

echo "\
$artist
$title
$album
$coverart
" > $dirtmp/radiometa
