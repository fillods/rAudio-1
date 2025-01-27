<heading data-status="mpd" class="status">Music Player Daemon<?=$istatus?></heading>
<div class="col-l text gr">
	Version
	<br>Database
</div>
<div class="col-r text">
	<div id="statusvalue"></div>
</div>
<div style="clear:both"></div>
<pre id="codempd" class="hide"></pre>

<div class="soundcard">
<heading data-status="asound" class="status">Output<?=$istatus?><?=$ihelp?></heading>
<pre id="codeasound" class="hide"></pre>
<div class="col-l">Device</div>
<div class="col-r">
	<select id="audiooutput"></select>
	<span <?=$classhelp?>>HDMI device available only when connected before boot.</span>
</div>
<div>
<div id="divhwmixer">
	<div class="col-l">Mixer Device</div>
	<div class="col-r">
		<select id="hwmixer"></select>
		<i id="setting-hwmixer" class="settingedit fa fa-volume"></i><br>
		<span <?=$classhelp?>>
			<i class="fa fa-volume"></i> <code>amixer</code> / <code>alsamixer</code>&ensp;volume control
		</span>
	</div>
</div>
<div class="col-l">Volume Control</div>
<div class="col-r">
	<select id="mixertype"></select>
	<span <?=$classhelp?>>
			Volume control for each device.
		<p>
			&bull; <code>None / 0dB</code> Best sound quality. (Use amplifier volume)
		<br>&bull; <code>Mixer device</code> Good and convenient. (Device hardware volume)
		<br>&bull; <code>MPD software</code> Software volume.
		</p>
	</span>
</div>
</div>
</div>

<div class="soundcard">
<heading>Bit-Perfect<?=$ihelp?></heading>
<div class="col-l">No Volume</div>
<div class="col-r">
	<input id="novolume" type="checkbox">
	<div class="switchlabel" for="novolume"></div>
	<span <?=$classhelp?>>
		Disable all manipulations for bit-perfect stream from MPD to DAC.
		<p>
			&bull; Mixer device volume set to 0dB - No amplitude manipulations.
		<br>&bull; Volume Control <code>None</code> - Hide Volume in Playback.
		<br>&bull; Disable Crossfade, Normalization and Replay Gain.
		</p>
	</span>
</div>

<div>
	<div class="col-l dop">DSD over PCM</div>
	<div class="col-r dop">
		<input id="dop" type="checkbox">
		<div class="switchlabel" for="dop"></div>
		<span <?=$classhelp?>>For DSD-capable devices without drivers dedicated for native DSD. Enable if there's no sound from the DAC.
			<br>DoP will repack 16bit DSD stream into 24bit PCM frames and transmit to the DAC. 
			Then PCM frames will be reassembled back to original DSD stream, COMPLETELY UNCHANGED, with expense of double bandwith.
			<br>On-board audio and non-DSD devices will always get DSD converted to PCM stream, no bit-perfect</span>
	</div>
</div>
</div>

<div>
<heading>Volume<?=$ihelp?></heading>
<div data-status="crossfade" <?=$classstatus?>>
	<a>Crossfade
	<br><gr>mpc<?=$istatus?></gr></a>
</div>
<div class="col-r">
	<input id="crossfade" <?=$chkenable?>>
	<div class="switchlabel" for="crossfade"></div>
	<i id="setting-crossfade" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
			<code>mpc crossfade N</code>
		<br>Fade-out to fade-in between songs.
	</span>
</div>
<pre id="codecrossfade" class="hide"></pre>
<div class="col-l double">
	<a>Normalization
	<br><gr>option</gr></a>
</div>
<div class="col-r">
	<input id="normalization" <?=$chknoset?>>
	<div class="switchlabel" for="normalization"></div>
	<span <?=$classhelp?>>
			<code>volume_normalization "yes"</code>
		<br>Normalize the volume level of songs as they play.
	</span>
</div>
<div class="col-l double">
	<a>Replay Gain
	<br><gr>option</gr></a>
</div>
<div class="col-r">
	<input id="replaygain" <?=$chkenable?>>
	<div class="switchlabel" for="replaygain"></div>
	<i id="setting-replaygain" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
			<code>replaygain "N"</code>
		<br>Set gain control to setting in replaygain tag.
		<br>Currently support: FLAC, Ogg Vorbis, Musepack, and MP3 (through ID3v2 ReplayGain tags, not APEv2)
	</span>
</div>
</div>

<div>
<heading data-status="mpdconf" class="status">Options<?=$istatus?><?=$ihelp?></heading>
<pre id="codempdconf" class="hide"></pre>
<div class="col-l double">
	<a>Auto Update
	<br><gr>Library</gr></a>
</div>
<div class="col-r">
	<input id="autoupdate"  <?=$chknoset?>>
	<div class="switchlabel" for="autoupdate"></div>
	<span <?=$classhelp?>>
			<code>auto_update "yes"</code>
		<br>Automatic update MPD database when files changed.
	</span>
</div>
<div class="col-l double">
	<a>FFmpeg
	<br><gr>decoder</gr></a>
</div>
<div class="col-r">
	<input id="ffmpeg"  <?=$chknoset?>>
	<div class="switchlabel" for="ffmpeg"></div>
	<span <?=$classhelp?>>
			<code>enable "yes"</code>
		<br>Should be disabled if not used for faster Sources update.
		<br>Decoder for audio filetypes:&emsp;<i id="filetype" class="fa fa-question-circle"></i>
		<div id="divfiletype" class="hide" style="margin-left: 20px"><?=( shell_exec( '/srv/http/bash/player.sh filetype' ) )?></div>
	</span>
</div>
<div class="col-l double">
	<a>Audio Buffer
	<br><gr>custom size</gr></a>
</div>
<div class="col-r">
	<input id="buffer" <?=$chkenable?>>
	<div class="switchlabel" for="buffer"></div>
	<i id="setting-buffer" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
			<code>audio_buffer_size "kB"</code>
		<br>Default buffer size: 4096 kB (24 seconds of CD-quality audio)
		<br>Increase to fix intermittent audio.
	</span>
</div>
<div class="col-l double">
		<a>Output Buffer
	<br><gr>custom size</gr></a>
</div>
<div class="col-r">
	<input id="bufferoutput" <?=$chkenable?>>
	<div class="switchlabel" for="bufferoutput"></div>
	<i id="setting-bufferoutput" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
		<code>max_output_buffer_size "kB"</code>
		<br>Default buffer size: 8192 kB
		<br>Increase to fix missing Album list with large Library.
	</span>
</div>
<div class="col-l double">
		<a>Resampling
	<br><gr>SoXR custom settings</gr></a>
</div>
<div class="col-r">
	<input id="soxr" <?=$chkenable?>>
	<div class="switchlabel" for="soxr"></div>
	<i id="setting-soxr" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
			<code>quality "custom"</code>
		<br>Default quality: very high
		<br>SoX Resampler custom settings:
		<p>
			&bull; Precision - Conversion precision (20 = HQ)
		<br>&bull; Phase Response (50 = Linear)
		<br>&bull; Passband End - 0dB point bandwidth to preserve (100 = Nyquist)
		<br>&bull; Stopband Begin - Aliasing/imaging control
		<br>&bull; Attenuation - Lowers the source to prevent clipping
		<br>&bull; Flags - Extra settings:
		<br> &emsp; 0 - Rolloff - small (<= 0.01 dB)
		<br> &emsp; 1 - Rolloff - medium (<= 0.35 dB)
		<br> &emsp; 2 - Rolloff - none - For Chebyshev bandwidth
		<br> &emsp; 8 - High precision - Increase irrational ratio accuracy
		<br> &emsp; 16 - Double precision - even if Precision <= 20
		<br> &emsp; 32 - Variable rate resampling
		</p>
	</span>
</div>
<div class="col-l">User's Custom Settings</div>
<div class="col-r">
	<input id="custom" <?=$chkenable?>>
	<div class="switchlabel" for="custom"></div>
	<i id="setting-custom" <?=$classsetting?>></i>
	<span <?=$classhelp?>>Insert settings into <code>/etc/mpd.conf</code>.</span>
</div>
</div>

<div style="clear:both"></div>
