<?php

trait OptionsFunction
{
	public function settingsOptionGroup($options = [])
	{
		$settings = [];
		foreach ($options as $option) {
			$optionType = $option[0] ? $option[0] : false;
			$optionName = $option[1] ? $option[1] : null;
			$optionExtras = $option[2] ? $option[2] : [];
			$setting = $this->settingsOption($optionType, $optionName, $optionExtras);
			array_push($settings, $setting);
		}
		return $settings;
	}
	
	public function settingsOption($type, $name = null, $extras = null)
	{
		$type = strtolower(str_replace('-', '', $type));
		$setting = [
			'name' => $name,
			'value' => $this->config[$name] ?? ''
		];
		switch ($type) {
			case 'enable':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Enable',
				];
				break;
			case 'auth':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Minimum Authentication',
					'options' => $this->groupOptions
				];
				break;
			case 'refresh':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Refresh Seconds',
					'options' => $this->timeOptions()
				];
				break;
			case 'combine':
			case 'combine-downloader':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Add to Combined Downloader',
				];
				break;
			case 'test':
				$settingMerge = [
					'type' => 'button',
					'label' => '',
					'icon' => 'fa fa-flask',
					'class' => 'pull-right',
					'text' => 'Test Connection',
					'attr' => 'onclick="testAPIConnection(\'' . $name . '\')"'
				];
				break;
			case 'url':
				$settingMerge = [
					'type' => 'input',
					'label' => 'URL',
					'help' => 'Please make sure to use local IP address and port - You also may use local dns name too.',
					'placeholder' => 'http(s)://hostname:port'
				];
				break;
			case 'multipleurl':
				$settingMerge = [
					'type' => 'select2',
					'class' => 'select2-multiple',
					'id' => $name . '-select',
					'label' => 'Multiple URL\'s',
					'help' => 'Please make sure to use local IP address and port - You also may use local dns name too.',
					'placeholder' => 'http(s)://hostname:port',
					'options' => $this->makeOptionsFromValues($this->config[$name]),
					'settings' => '{tags: true, selectOnClose: true, closeOnSelect: true}',
				];
				break;
			case 'multiple':
				$settingMerge = [
					'type' => 'select2',
					'class' => 'select2-multiple',
					'id' => $name . '-select',
					'label' => 'Multiple Values\'s',
					'options' => $this->makeOptionsFromValues($this->config[$name]),
					'settings' => '{tags: true, selectOnClose: true, closeOnSelect: true}',
				];
				break;
			case 'username':
				$settingMerge = [
					'type' => 'input',
					'label' => 'Username',
				];
				break;
			case 'password':
				$settingMerge = [
					'type' => 'password',
					'label' => 'Password',
				];
				break;
			case 'passwordalt':
				$settingMerge = [
					'type' => 'password-alt',
					'label' => 'Password',
				];
				break;
			case 'apikey':
			case 'token':
				$settingMerge = [
					'type' => 'password-alt',
					'label' => 'API Key/Token',
				];
				break;
			case 'multipleapikey':
			case 'multipletoken':
				$settingMerge = [
					'type' => 'select2',
					'class' => 'select2-multiple',
					'id' => $name . '-select',
					'label' => 'Multiple API Key/Token\'s',
					'options' => $this->makeOptionsFromValues($this->config[$name]),
					'settings' => '{tags: true, theme: "default password-alt", selectOnClose: true, closeOnSelect: true}',
				];
				break;
			case 'notice':
				$settingMerge = [
					'type' => 'html',
					'override' => 12,
					'label' => '',
					'html' => '
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-' . ($extras['notice'] ?? 'info') . '">
									<div class="panel-heading">
										<span lang="en">' . ($extras['title'] ?? 'Attention') . '</span>
									</div>
									<div class="panel-wrapper collapse in" aria-expanded="true">
										<div class="panel-body">
											<span lang="en">' . ($extras['body'] ?? '') . '</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						'
				];
				break;
			case 'socks':
				$settingMerge = [
					'type' => 'html',
					'override' => 12,
					'label' => '',
					'html' => '
						<div class="panel panel-default">
							<div class="panel-wrapper collapse in">
								<div class="panel-body">' . $this->socksHeadingHTML($name) . '</div>
							</div>
						</div>'
				];
				break;
			case 'about':
				$settingMerge = [
					'type' => 'html',
					'override' => 12,
					'label' => '',
					'html' => '
						<div class="panel panel-default">
							<div class="panel-wrapper collapse in">
								<div class="panel-body">
									<h3 lang="en">' . ucwords($name) . ' Homepage Item</h3>
									<p lang="en">' . $extras["about"] . '</p>
								</div>
							</div>
						</div>'
				];
				break;
			case 'title':
				$settingMerge = [
					'type' => 'input',
					'label' => 'Title',
					'help' => 'Sets the title of this homepage module',
				];
				break;
			case 'toggletitle':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Toggle Title',
					'help' => 'Shows/hides the title of this homepage module'
				];
				break;
			case 'disablecertcheck':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Disable Certificate Check',
				];
				break;
			case 'usecustomcertificate':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Use Custom Certificate',
				];
				break;
			case 'hideseeding':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Hide Seeding',
				];
			case 'hidecompleted':
				$settingMerge = [
					'type' => 'switch',
					'label' => 'Hide Completed',
				];
				break;
			case 'limit':
				$settingMerge = [
					'type' => 'number',
					'label' => 'Item Limit',
				];
				break;
			case 'mediasearchserver':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Media Search Server',
					'options' => $this->mediaServerOptions()
				];
				break;
			case 'imagecachequality':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Image Cache Quality',
					'options' => [
						[
							'name' => 'Low',
							'value' => '.5'
						],
						[
							'name' => '1x',
							'value' => '1'
						],
						[
							'name' => '2x',
							'value' => '2'
						],
						[
							'name' => '3x',
							'value' => '3'
						]
					]
				];
				break;
			case 'blank':
				$settingMerge = [
					'type' => 'blank',
					'label' => '',
				];
				break;
			case 'plexlibraryexclude':
				$settingMerge = [
					'type' => 'select2',
					'class' => 'select2-multiple',
					'id' => $name . '-exclude-select',
					'label' => 'Libraries to Exclude',
					'options' => $extras['options']
				];
				break;
			// HTML ITEMS
			case 'precodeeditor':
				$settingMerge = [
					'type' => 'textbox',
					'class' => 'hidden ' . $name . 'Textarea',
					'label' => '',
				];
				break;
			case 'codeeditor':
				$settingMerge = [
					'type' => 'html',
					'override' => 12,
					'label' => 'Custom Code',
					'html' => '<div id="' . $name . 'Editor" style="height:300px">' . htmlentities($this->config[$name]) . '</div>'
				];
				break;
			// CALENDAR ITEMS
			case 'calendarstart':
				$settingMerge = [
					'type' => 'number',
					'label' => '# of Days Before'
				];
				break;
			case 'calendarend':
				$settingMerge = [
					'type' => 'number',
					'label' => '# of Days After'
				];
				break;
			case 'calendarstartingday':
			case 'calendarstartday':
			case 'calendarstart':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Start Day',
					'options' => $this->daysOptions()
				];
				break;
			case 'calendardefaultview':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Default View',
					'options' => $this->calendarDefaultOptions()
				];
				break;
			case 'calendartimeformat':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Time Format',
					'options' => $this->timeFormatOptions()
				];
				break;
			case 'calendarlocale':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Locale',
					'options' => $this->calendarLocaleOptions()
				];
				break;
			case 'calendarlimit':
				$settingMerge = [
					'type' => 'select',
					'label' => 'Items Per Day',
					'options' => $this->limitOptions()
				];
				break;
			default:
				$settingMerge = [
					'type' => strtolower($type),
					'label' => ''
				];
				break;
		}
		$setting = array_merge($settingMerge, $setting);
		if ($extras) {
			if (gettype($extras) == 'array') {
				$setting = array_merge($setting, $extras);
			}
		}
		return $setting;
	}
	
	public function makeOptionsFromValues($values = null)
	{
		$formattedValues = [];
		if (strpos($values, ',') !== false) {
			$explode = explode(',', $values);
			foreach ($explode as $item) {
				$formattedValues[] = [
					'name' => $item,
					'value' => $item
				];
			}
		} elseif ($values == '') {
			$formattedValues = '';
		} else {
			$formattedValues[] = [
				'name' => $values,
				'value' => $values
			];
		}
		return $formattedValues;
	}
	
	public function calendarLocaleOptions()
	{
		return [
			[
				'name' => 'Arabic (Standard)',
				'value' => 'ar',
			],
			[
				'name' => 'Arabic (Morocco)',
				'value' => 'ar-ma',
			],
			[
				'name' => 'Arabic (Saudi Arabia)',
				'value' => 'ar-sa'
			],
			[
				'value' => 'ar-tn',
				'name' => 'Arabic (Tunisia)'
			],
			[
				'value' => 'bg',
				'name' => 'Bulgarian'
			],
			[
				'value' => 'ca',
				'name' => 'Catalan'
			],
			[
				'value' => 'cs',
				'name' => 'Czech'
			],
			[
				'value' => 'da',
				'name' => 'Danish'
			],
			[
				'value' => 'de',
				'name' => 'German (Standard)'
			],
			[
				'value' => 'de-at',
				'name' => 'German (Austria)'
			],
			[
				'value' => 'el',
				'name' => 'Greek'
			],
			[
				'value' => 'en',
				'name' => 'English'
			],
			[
				'value' => 'en-au',
				'name' => 'English (Australia)'
			],
			[
				'value' => 'en-ca',
				'name' => 'English (Canada)'
			],
			[
				'value' => 'en-gb',
				'name' => 'English (United Kingdom)'
			],
			[
				'value' => 'es',
				'name' => 'Spanish'
			],
			[
				'value' => 'fa',
				'name' => 'Farsi'
			],
			[
				'value' => 'fi',
				'name' => 'Finnish'
			],
			[
				'value' => 'fr',
				'name' => 'French (Standard)'
			],
			[
				'value' => 'fr-ca',
				'name' => 'French (Canada)'
			],
			[
				'value' => 'he',
				'name' => 'Hebrew'
			],
			[
				'value' => 'hi',
				'name' => 'Hindi'
			],
			[
				'value' => 'hr',
				'name' => 'Croatian'
			],
			[
				'value' => 'hu',
				'name' => 'Hungarian'
			],
			[
				'value' => 'id',
				'name' => 'Indonesian'
			],
			[
				'value' => 'is',
				'name' => 'Icelandic'
			],
			[
				'value' => 'it',
				'name' => 'Italian'
			],
			[
				'value' => 'ja',
				'name' => 'Japanese'
			],
			[
				'value' => 'ko',
				'name' => 'Korean'
			],
			[
				'value' => 'lt',
				'name' => 'Lithuanian'
			],
			[
				'value' => 'lv',
				'name' => 'Latvian'
			],
			[
				'value' => 'nb',
				'name' => 'Norwegian (Bokmal)'
			],
			[
				'value' => 'nl',
				'name' => 'Dutch (Standard)'
			],
			[
				'value' => 'pl',
				'name' => 'Polish'
			],
			[
				'value' => 'pt',
				'name' => 'Portuguese'
			],
			[
				'value' => 'pt-br',
				'name' => 'Portuguese (Brazil)'
			],
			[
				'value' => 'ro',
				'name' => 'Romanian'
			],
			[
				'value' => 'ru',
				'name' => 'Russian'
			],
			[
				'value' => 'sk',
				'name' => 'Slovak'
			],
			[
				'value' => 'sl',
				'name' => 'Slovenian'
			],
			[
				'value' => 'sr',
				'name' => 'Serbian'
			],
			[
				'value' => 'sv',
				'name' => 'Swedish'
			],
			[
				'value' => 'th',
				'name' => 'Thai'
			],
			[
				'value' => 'tr',
				'name' => 'Turkish'
			],
			[
				'value' => 'uk',
				'name' => 'Ukrainian'
			],
			[
				'value' => 'vi',
				'name' => 'Vietnamese'
			],
			[
				'value' => 'zh-cn',
				'name' => 'Chinese (PRC)'
			],
			[
				'value' => 'zh-tw',
				'name' => 'Chinese (Taiwan)'
			]
		];
		
	}
	
	public function daysOptions()
	{
		return array(
			array(
				'name' => 'Sunday',
				'value' => '0'
			),
			array(
				'name' => 'Monday',
				'value' => '1'
			),
			array(
				'name' => 'Tueday',
				'value' => '2'
			),
			array(
				'name' => 'Wednesday',
				'value' => '3'
			),
			array(
				'name' => 'Thursday',
				'value' => '4'
			),
			array(
				'name' => 'Friday',
				'value' => '5'
			),
			array(
				'name' => 'Saturday',
				'value' => '6'
			)
		);
	}
	
	public function mediaServerOptions()
	{
		return array(
			array(
				'name' => 'N/A',
				'value' => ''
			),
			array(
				'name' => 'Plex',
				'value' => 'plex'
			),
			array(
				'name' => 'Emby [Not Available]',
				'value' => 'emby'
			)
		);
	}
	
	public function ombiTvOptions()
	{
		return array(
			array(
				'name' => 'All Seasons',
				'value' => 'all'
			),
			array(
				'name' => 'First Season Only',
				'value' => 'first'
			),
			array(
				'name' => 'Last Season Only',
				'value' => 'last'
			),
		);
	}
	
	public function limitOptions()
	{
		return array(
			array(
				'name' => '1 Item',
				'value' => '1'
			),
			array(
				'name' => '2 Items',
				'value' => '2'
			),
			array(
				'name' => '3 Items',
				'value' => '3'
			),
			array(
				'name' => '4 Items',
				'value' => '4'
			),
			array(
				'name' => '5 Items',
				'value' => '5'
			),
			array(
				'name' => '6 Items',
				'value' => '6'
			),
			array(
				'name' => '7 Items',
				'value' => '7'
			),
			array(
				'name' => '8 Items',
				'value' => '8'
			),
			array(
				'name' => 'Unlimited',
				'value' => '1000'
			),
		);
	}
	
	public function notificationTypesOptions()
	{
		return array(
			array(
				'name' => 'Toastr',
				'value' => 'toastr'
			),
			array(
				'name' => 'Izi',
				'value' => 'izi'
			),
			array(
				'name' => 'Alertify',
				'value' => 'alertify'
			),
			array(
				'name' => 'Noty',
				'value' => 'noty'
			),
		);
	}
	
	public function notificationPositionsOptions()
	{
		return array(
			array(
				'name' => 'Bottom Right',
				'value' => 'br'
			),
			array(
				'name' => 'Bottom Left',
				'value' => 'bl'
			),
			array(
				'name' => 'Bottom Center',
				'value' => 'bc'
			),
			array(
				'name' => 'Top Right',
				'value' => 'tr'
			),
			array(
				'name' => 'Top Left',
				'value' => 'tl'
			),
			array(
				'name' => 'Top Center',
				'value' => 'tc'
			),
			array(
				'name' => 'Center',
				'value' => 'c'
			),
		);
	}
	
	public function timeOptions()
	{
		return array(
			array(
				'name' => '2.5',
				'value' => '2500'
			),
			array(
				'name' => '5',
				'value' => '5000'
			),
			array(
				'name' => '10',
				'value' => '10000'
			),
			array(
				'name' => '15',
				'value' => '15000'
			),
			array(
				'name' => '30',
				'value' => '30000'
			),
			array(
				'name' => '60 [1 Minute]',
				'value' => '60000'
			),
			array(
				'name' => '300 [5 Minutes]',
				'value' => '300000'
			),
			array(
				'name' => '600 [10 Minutes]',
				'value' => '600000'
			),
			array(
				'name' => '900 [15 Minutes]',
				'value' => '900000'
			),
			array(
				'name' => '1800 [30 Minutes]',
				'value' => '1800000'
			),
			array(
				'name' => '3600 [1 Hour]',
				'value' => '3600000'
			),
		);
		
	}
	
	public function netdataOptions()
	{
		return [
			[
				'name' => 'Disk Read',
				'value' => 'disk-read',
			],
			[
				'name' => 'Disk Write',
				'value' => 'disk-write',
			],
			[
				'name' => 'CPU',
				'value' => 'cpu'
			],
			[
				'name' => 'Network Inbound',
				'value' => 'net-in',
			],
			[
				'name' => 'Network Outbound',
				'value' => 'net-out',
			],
			[
				'name' => 'Used RAM',
				'value' => 'ram-used',
			],
			[
				'name' => 'Used Swap',
				'value' => 'swap-used',
			],
			[
				'name' => 'Disk space used',
				'value' => 'disk-used',
			],
			[
				'name' => 'Disk space available',
				'value' => 'disk-avail',
			],
			[
				'name' => 'Custom',
				'value' => 'custom',
			]
		];
	}
	
	public function netdataChartOptions()
	{
		return [
			[
				'name' => 'Easy Pie Chart',
				'value' => 'easypiechart',
			],
			[
				'name' => 'Gauge',
				'value' => 'gauge'
			]
		];
	}
	
	public function netdataColourOptions()
	{
		return [
			[
				'name' => 'Red',
				'value' => 'fe3912',
			],
			[
				'name' => 'Green',
				'value' => '46e302',
			],
			[
				'name' => 'Purple',
				'value' => 'CC22AA'
			],
			[
				'name' => 'Blue',
				'value' => '5054e6',
			],
			[
				'name' => 'Yellow',
				'value' => 'dddd00',
			],
			[
				'name' => 'Orange',
				'value' => 'd66300',
			]
		];
	}
	
	public function netdataSizeOptions()
	{
		return [
			[
				'name' => 'Large',
				'value' => 'lg',
			],
			[
				'name' => 'Medium',
				'value' => 'md',
			],
			[
				'name' => 'Small',
				'value' => 'sm'
			]
		];
	}
	
	public function timeFormatOptions()
	{
		return array(
			array(
				'name' => '6pm',
				'value' => 'h(:mm)a'
			),
			array(
				'name' => '6:00pm',
				'value' => 'h:mma'
			),
			array(
				'name' => '6:00',
				'value' => 'h:mm'
			),
			array(
				'name' => '18',
				'value' => 'H(:mm)'
			),
			array(
				'name' => '18:00',
				'value' => 'H:mm'
			)
		);
	}
	
	public function rTorrentSortOptions()
	{
		return array(
			array(
				'name' => 'Date Desc',
				'value' => 'dated'
			),
			array(
				'name' => 'Date Asc',
				'value' => 'datea'
			),
			array(
				'name' => 'Hash Desc',
				'value' => 'hashd'
			),
			array(
				'name' => 'Hash Asc',
				'value' => 'hasha'
			),
			array(
				'name' => 'Name Desc',
				'value' => 'named'
			),
			array(
				'name' => 'Name Asc',
				'value' => 'namea'
			),
			array(
				'name' => 'Size Desc',
				'value' => 'sized'
			),
			array(
				'name' => 'Size Asc',
				'value' => 'sizea'
			),
			array(
				'name' => 'Label Desc',
				'value' => 'labeld'
			),
			array(
				'name' => 'Label Asc',
				'value' => 'labela'
			),
			array(
				'name' => 'Status Desc',
				'value' => 'statusd'
			),
			array(
				'name' => 'Status Asc',
				'value' => 'statusa'
			),
		);
	}
	
	public function qBittorrentApiOptions()
	{
		return array(
			array(
				'name' => 'V1',
				'value' => '1'
			),
			array(
				'name' => 'V2',
				'value' => '2'
			),
		);
	}
	
	public function qBittorrentSortOptions()
	{
		return array(
			array(
				'name' => 'Hash',
				'value' => 'hash'
			),
			array(
				'name' => 'Name',
				'value' => 'name'
			),
			array(
				'name' => 'Size',
				'value' => 'size'
			),
			array(
				'name' => 'Progress',
				'value' => 'progress'
			),
			array(
				'name' => 'Download Speed',
				'value' => 'dlspeed'
			),
			array(
				'name' => 'Upload Speed',
				'value' => 'upspeed'
			),
			array(
				'name' => 'Priority',
				'value' => 'priority'
			),
			array(
				'name' => 'Number of Seeds',
				'value' => 'num_seeds'
			),
			array(
				'name' => 'Number of Seeds in Swarm',
				'value' => 'num_complete'
			),
			array(
				'name' => 'Number of Leechers',
				'value' => 'num_leechs'
			),
			array(
				'name' => 'Number of Leechers in Swarm',
				'value' => 'num_incomplete'
			),
			array(
				'name' => 'Ratio',
				'value' => 'ratio'
			),
			array(
				'name' => 'ETA',
				'value' => 'eta'
			),
			array(
				'name' => 'State',
				'value' => 'state'
			),
			array(
				'name' => 'Category',
				'value' => 'category'
			)
		);
	}
	
	public function calendarDefaultOptions()
	{
		return array(
			array(
				'name' => 'Month',
				'value' => 'month'
			),
			array(
				'name' => 'Day',
				'value' => 'basicDay'
			),
			array(
				'name' => 'Week',
				'value' => 'basicWeek'
			),
			array(
				'name' => 'List',
				'value' => 'list'
			)
		);
	}
}
