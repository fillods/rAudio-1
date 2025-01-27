<?php
$i2slist = json_decode( file_get_contents( '/srv/http/settings/system-i2s.json' ) );
$selecti2s = '<select id="i2smodule">';
foreach( $i2slist as $name => $sysname ) {
	$selecti2s.= '<option value="'.$sysname.'">'.$name.'</option>';
}
$selecti2s.= '</select>';
$timezonelist = timezone_identifiers_list();
$selecttimezone = '<select id="timezone">';
foreach( $timezonelist as $key => $zone ) {
	$datetime = new DateTime( 'now', new DateTimeZone( $zone ) );
	$offset = $datetime->format( 'P' );
	$zonename = preg_replace( [ '/_/', '/\//' ], [ ' ', ' <gr>&middot;</gr> ' ], $zone );
	$selecttimezone.= '<option value="'.$zone.'">'.$zonename.'&ensp;'.$offset.'</option>\n';
}
$selecttimezone.= '</select>';
$helpstatus = '<i class="fa fa-code w2x"></i>Tap label: <code>systemctl status SERVICE</code></span>';
?>
<heading data-status="journalctl" class="status">System<?=$istatus?></heading>
<div id="systemlabel" class="col-l text gr">
		Version
	<br>OS Kernel
	<br>Hardware
	<br>SoC
	<br>CPU
</div>
<div id="systemvalue" class="col-r text"></div> 
<div style="clear:both"></div>
<pre id="codejournalctl" class="hide"></pre>

<div>
<heading>Status<i id="refresh" class="fa fa-refresh"></i><?=$ihelp?></heading>
<div id="statuslabel" class="col-l text gr">
		CPU Load
	<br>CPU Temperatue
	<br>Time
	<br>Up Time
	<br>Boot Duration
</div>
<div id="status" class="col-r text"></div>

<div class="col-l"></div>
<div class="col-r">
	<span <?=$classhelp?>>
		<br><gr><i class="fa fa-refresh"></i>&emsp;Toggle refresh every 10 seconds.</gr>
		<br>
		<br>CPU Load:
		<p>
			&bull; Average number of processes which are being executed and in waiting.
		<br>&bull; calculated over 1, 5 and 15 minutes.
		<br>&bull; Each one should not be constantly over 0.75 x CPU cores.
		</p>
		<br>CPU temperature:
		<p>
			&bull; 80-84°C: ARM cores throttled.
		<br>&bull; 85°C: ARM cores and GPU throttled.
		<br>&bull; RPi 3B+: 60°C soft limit (optimized throttling)
		</p>
		<div id="throttled">
			<br><i class="fa fa-warning"></i> Under-voltage warning: <code>vcgencmd get_throttled</code>
			<p>
				&bull; "occurred" - Events happenned.
			<br>&bull; "currently detected" - Currently under minimum limit. System unstable is very likely.
			<br>&bull; More info - <a href="https://www.raspberrypi.org/documentation/raspbian/applications/vcgencmd.md">vcgencmd</a>
		</p>
		</div>
	</span>
</div>
</div>

<div>
<?php
$uid = exec( "$sudo/id -u mpd" );
$gid = exec( "$sudo/id -g mpd" );
?>
<heading data-status="mount" class="noline status">Storage<?=$istatus?><i id="addnas" class="fa fa-plus-circle"></i><?=$ihelp?></heading>
<ul id="list" class="entries" data-uid="<?=$uid?>" data-gid="<?=$gid?>"></ul>
<p class="brhalf"></p>
<span <?=$classhelp?>>
	Available sources, local USB and NAS mounts, for Library.
	<br>USB drive will be found and mounted automatically. Network shares must be manually configured.
	<br>
	<br><i class="fa fa-plus-circle"></i>&ensp; Add network share commands:
	<br> &emsp; <gr>(If mount failed, try in SSH terminal.)</gr>
	<br>#1:
	<pre>mkdir -p "/mnt/MPD/NAS/<bll>NAME</bll>"</pre>
	#2:
	<br>CIFS:
	<pre>mount -t cifs "//<bll>IP</bll>/<bll>SHARENAME</bll>" "/mnt/MPD/NAS/<bll>NAME</bll>" -o noauto,username=<bll>USER</bll>,password=<bll>PASSWORD</bll>,uid=<?=$uid?>,gid=<?=$gid?>,iocharset=utf8</pre>
	NFS:
	<pre>mount -t nfs "<bll>IP</bll>:<bll>/SHARE/PATH</bll>" "/mnt/MPD/NAS/<bll>NAME</bll>" -o defaults,noauto,bg,soft,timeo=5</pre>
	(Append more options if required.)
</span>
<pre id="codemount" class="hide"></pre>
</div>

<div>
<heading data-status="rfkill" class="status">Wireless<?=$istatus?></heading>
<pre id="coderfkill" class="hide"></pre>
<div data-status="bluetoothctl" <?=$classstatus?>>
	<a>Bluetooth
	<br><gr><?=$istatus?></gr></a><i class="fa fa-bluetooth"></i>
</div>
<div class="col-r">
	<input id="bluetooth" <?=$chkenable?>>
	<div class="switchlabel" for="bluetooth"></div>
	<i id="setting-bluetooth" <?=$classsetting?>></i>
</div>
<pre id="codebluetoothctl" class="hide"></pre>
<div data-status="iw" <?=$classstatus?>>
	<a>Wi-Fi
	<br><gr><?=$istatus?></gr></a><i class="fa fa-wifi"></i>
</div>
<div class="col-r">
	<input id="wlan" type="checkbox">
	<div class="switchlabel" for="onboardwlan"></div>
</div>
<pre id="codeiw" class="hide"></pre>
</div>

<div>
<heading data-status="configtxt" class="status">GPIO Devices<?=$istatus?><?=$ihelp?></heading>
<pre id="codeconfigtxt" class="hide"></pre>
<div <?=$classhelp?>>
	GPIO pin reference: <a id="gpioimgtxt">RPi J8 &ensp;<i class="fa fa-chevron-down"></i></a><a id="fliptxt">&emsp;(Tap image to flip)</a>
	<img id="gpiopin" src="/assets/img/RPi3_GPIO-flip.<?=$time?>.svg">
	<img id="gpiopin1" src="/assets/img/RPi3_GPIO.<?=$time?>.svg">
</div>
<div class="col-l single">Audio - I²S<i class="fa fa-i2saudio"></i></div>
<div class="col-r i2s">
	<div id="divi2smodulesw">
		<input id="i2smodulesw" type="checkbox">
		<div class="switchlabel" for="i2smodulesw"></div>
	</div>
	<div id="divi2smodule">
		<?=$selecti2s?>
	</div>
	<span <?=$classhelp?>>I²S audio modules are not plug-and-play capable. Select a driver for installed device.</span>
</div>
<div class="col-l double">
	<a>Character LCD
	<br><gr>HD44780</gr></a><i class="fa fa-lcdchar"></i>
</div>
<div class="col-r">
	<input id="lcdchar" <?=$chkenable?>>
	<div class="switchlabel" for="lcdchar"></div>
	<i id="setting-lcdchar" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
			<a href="https://github.com/dbrgn/RPLCD">RPLCD</a> - Python library for Hitachi HD44780 controller.
		<br>&bull; Support 16x2, 20x4 and 40x4 LCD modules.
		<br>&bull; <a href="https://rplcd.readthedocs.io/en/latest/getting_started.html#wiring">Wiring</a>
		<br><i class="fa fa-warning"></i> Precaution for LCD with I²C backpack: <a href="https://www.instructables.com/Raspberry-Pi-Using-1-I2C-LCD-Backpacks-for-1602-Sc/">5V to 3.3V I²C + 5V LCD Mod</a>
	</span>
</div>
<div data-status="powerbutton" <?=$classstatus?>>
	<a>Power Button
	<br><gr>WiringPi <?=$istatus?></gr></a><i class="fa fa-power"></i>
</div>
<div class="col-r">
	<input id="powerbutton" class="enable" type="checkbox">
	<div class="switchlabel" for="powerbutton"></div>
	<i id="setting-powerbutton" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
		Power button for on/off rAudio.
		<br>&bull; <a href="https://github.com/rern/rAudio-1/discussions/181#discussion-3100261">Wiring</a>
	</span>
</div>
<pre id="codepowerbutton" class="hide"></pre>
<div class="col-l double">
	<a>Relay Module
	<br><gr>WiringPi</gr></a><i class="fa fa-relays"></i>
</div>
<div class="col-r">
	<input id="relays" <?=$chknoset?>>
	<div class="switchlabel" for="relays"></div>
	<i id="setting-relays" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
		<a href="https://sourceforge.net/projects/raspberry-gpio-python/">RPi.GPIO</a> - Python module to control GPIO.
		<br>&bull; Control GPIO-connected relay module for power on / off equipments.
		<br>&bull; This can be enabled and run as a test without a connected relay module.
	</span>
</div>
<div class="col-l double">
	<a>TFT 3.5" LCD
	<br><gr>420x320</gr></a><i class="fa fa-lcd"></i>
</div>
<div class="col-r">
	<input id="lcd" <?=$chknoset?>>
	<div class="switchlabel" for="lcd"></div>
	<i id="setting-lcd" <?=$classsetting?>></i>
	<span <?=$classhelp?>>
		For 3.5" 420x320 pixels TFT LCD with resistive touchscreen.
	<br><i class="fa fa-gear"></i>&ensp;Calibrate touchscreen precision.
	</span>
</div>
</div>

<div>
<heading>Environment<?=$ihelp?></heading>
<div class="col-l double">
	<a>Name
	<br><gr>hostname</gr></a><i class="fa fa-plus-r"></i>
</div>
<div class="col-r">
	<input type="text" id="hostname" readonly>
	<span <?=$classhelp?>>Name for Renderers, Streamers, RPi access point, Bluetooth and system hostname.</span>
</div>
<div class="col-l double">
	<a>Timezone
	<br><gr>NTP, RegDom</gr></a><i class="fa fa-globe"></i>
</div>
<div class="col-r">
	<?=$selecttimezone?>
	<i id="setting-regional" class="settingedit fa fa-gear"></i>
	<span <?=$classhelp?>>
		Wi-Fi regulatory domain:
		<p>
			&bull; 00 = Least common denominator settings, channels and transmit power are permitted in all countries.
		<br>&bull; Active regulatory domian may be reassigned by connected router.
		</p>
	</span>
</div>
<div data-status="soundprofile" class="col-l icon double status">
		<a>Sound Profile
	<br><gr>kernel <?=$istatus?></gr></a><i class="fa fa-soundprofile"></i>
</div>
<div class="col-r">
	<input id="soundprofile" <?=$chkenable?>>
	<div class="switchlabel" for="soundprofile"></div>
	<i id="setting-soundprofile" <?=$classsetting?>></i>
	<span <?=$classhelp?>>Tweak kernel parameters for <a htef="https://www.runeaudio.com/forum/sound-signatures-t2849.html">sound profiles</a>.</span>
</div>
<pre id="codesoundprofile" class="hide"></pre>
</div>

<div>
<heading id="backuprestore">Settings and Data<?=$ihelp?></heading>
<div data-status="backup" class="col-l single">Backup<i class="fa fa-sd"></i></div>
<div class="col-r">
	<input id="backup" type="checkbox">
	<div class="switchlabel" for="backup"></div>
	<span <?=$classhelp?>>
			Backup all settings and Library database:
		<p>&bull; Settings
		<br>&bull; Library database
		<br>&bull; Saved playlists
		<br>&bull; Bookmarks
		<br>&bull; Lyrics
		<br>&bull; WebRadios
		</p>
	</span>
</div>

<div data-status="restore" class="col-l single">Restore<i class="fa fa-sd-restore"></i></div>
<div class="col-r">
	<input id="restore" type="checkbox">
	<div class="switchlabel" for="restore"></div>
	<span <?=$classhelp?>>Restore all settings and Library database from a backup file. The system will reboot after finished.</span>
</div>
</div>
<?php
$listui = [
	  'HTML5-Color-Picker'  => 'https://github.com/NC22/HTML5-Color-Picker'
	, 'Inconsolata font'    => 'https://github.com/google/fonts/tree/main/ofl/inconsolata'
	, 'jQuery'              => 'https://jquery.com/'
	, 'jQuery Mobile'       => 'https://jquerymobile.com/'
	, 'jQuery Selectric'    => 'https://github.com/lcdsantos/jQuery-Selectric'
	, 'Lato-Fonts'          => 'http://www.latofonts.com/lato-free-fonts'
	, 'LazyLoad'            => 'https://github.com/verlok/lazyload'
	, 'pica'                => 'https://github.com/nodeca/pica'
	, 'QR Code generator'   => 'https://github.com/datalog/qrcode-svg'
	, 'roundSlider'         => 'https://github.com/soundar24/roundSlider'
	, 'simple-keyboard'     => 'https://github.com/hodgef/simple-keyboard/'
	, 'Sortable'            => 'https://github.com/SortableJS/Sortable'
];
$uihtml = '';
foreach( $listui as $name => $link ) {
	$uihtml.= '<a href="'.$link.'">'.$name.'</a><br>';
}
$version = file_get_contents( '/srv/http/data/system/version' );
?>
<br>
<heading>About</heading>
<i class="fa fa-plus-r fa-lg gr"></i>&ensp;<a href="https://github.com/rern/rAudio-<?=$version?>/discussions">r A u d i o&emsp;<?=$version?></a>
<br><gr>by</gr>&emsp;r e r n
<br>&nbsp;
<div>
<heading class="sub">Back End<?=$ihelp?></heading>
<span <?=$classhelp?>>
	<a href="https://www.archlinuxarm.org">ArchLinuxArm</a>
	<br><a id="pkg">Packages <i class="fa fa-chevron-down"></i></a>
	<div id="pkglist" class="hide"></div>
</span>
</div>
<div>
<heading class="sub">Front End<?=$ihelp?></heading>
<span <?=$classhelp?>>
	<?=$uihtml?>
</span>
</div>
<div>
<heading class="sub">Data<?=$ihelp?></heading>
<span <?=$classhelp?>>
	<a href="https://www.last.fm">last.fm</a><gr> - Coverarts and artist biographies</gr><br>
	<a href="https://webservice.fanart.tv">fanart.tv</a><gr> - Coverarts and artist images</gr><br>
	<a href="https://radioparadise.com">Radio Paradise</a><gr> - Coverarts of their own (default Webradio stations)</gr><br>
	<a href="https://www.fip.fr/">Fip</a><gr> - Coverarts of their own</gr><br>
	<a href="https://www.francemusique.fr/">France Musique</a><gr> - Coverarts of their own</gr>
</span>
</div>

<div style="clear: both"></div>
