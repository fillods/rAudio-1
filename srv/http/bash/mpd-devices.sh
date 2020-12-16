#!/bin/bash

# get hardware devices data with 'aplay' and amixer
# - aplay - get card index, sub-device index and aplayname
# - mixer device
#    - from file if manually set
#    - from 'amixer'
#        - if more than 1, filter with 'Digital\|Master' | get 1st one
# - mixer_type
#    - from file if manually set
#    - set as hardware if mixer device available
#    - if nothing, set as software
dirsystem=/srv/http/data/system

aplay=$( aplay -l | grep '^card' )
[[ -z $aplay ]] && echo -1 && exit
#aplay+=$'\ncard 1: sndrpiwsp [snd_rpi_wsp], device 0: WM5102 AiFi wm5102-aif1-0 []'

cardL=$( echo "$aplay" | wc -l )
audioaplayname=$( cat $dirsystem/audio-aplayname 2> /dev/null )

readarray -t lines <<<"$aplay"
for line in "${lines[@]}"; do
	hw=$( echo $line | sed 's/card \(.*\):.*device \(.*\):.*/hw:\1,\2/' )
	card=${hw:3:1}
	device=${hw: -1}
	aplayname=$( echo $line \
					| awk -F'[][]' '{print $2}' \
					| sed 's/^snd_rpi_//; s/_/-/g; s/wsp/rpi-cirrus-wm5102/' ) # some aplay -l: snd_rpi_xxx_yyy > xxx-yyy
	if [[ $aplayname == $audioaplayname ]]; then
		name=$( cat $dirsystem/audio-output )
	else
		name=$( echo $aplayname | sed 's/bcm2835/On-board/' )
	fi
	mixertype=hardware
	hwmixerfile=$dirsystem/hwmixer-$aplayname
	if [[ -e $hwmixerfile ]]; then # manual
		mixers=2
		hwmixer=$( cat "$hwmixerfile" )
		mixermanual=$hwmixer
	elif [[ $aplayname == rpi-cirrus-wm5102 ]]; then
		mixers=4
		hwmixer='HPOUT2 Digital'
		mixermanual=
	else
		amixer=$( amixer -c $card scontents \
			| grep -A2 'Simple mixer control' \
			| grep -v 'Capabilities' \
			| tr -d '\n' \
			| sed 's/--/\n/g' \
			| grep 'Playback channels' \
			| sed "s/.*'\(.*\)',\(.\) .*/\1 \2/; s/ 0$//" \
			| awk '!a[$0]++' )
		mixers=$( echo "$amixer" | wc -l )
		if (( $mixers == 0 )); then
			hwmixer=
			mixertype=software
		elif (( $mixers == 1 )); then
			hwmixer=$amixer
		else
			hwmixer=$( echo "$amixer" | grep 'Digital\|Master' | head -1 )
			[[ -z $hwmixer ]] && hwmixer=$( echo "$amixer" | head -1 )
		fi
		mixermanual=
	fi
	
	[[ -e "$dirsystem/dop-$name" ]] && dop=1 || dop=0
	
	Aaplayname+=( "$aplayname" )
	Acard+=( "$card" )
	Adevice+=( "$device" )
	Adop+=( "$dop" )
	Ahw+=( "$hw" )
	Ahwmixer+=( "$hwmixer" )
	Amixers+=( "$mixers" )
	Amixermanual+=( "$mixermanual" )
	Amixertype+=( "$mixertype" )
	Aname+=( "$name" )
done
