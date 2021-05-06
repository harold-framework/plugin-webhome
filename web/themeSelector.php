<?php

class ThemeSelector {

	public $theme;
	public $conversionTable = [
		"DARK" => "LIGHT",
		"LIGHT" => "DARK"
	];
	public $validThemes;

	function __construct( $apiData ) {
		$this->apiData = $apiData;
		$this->validThemes = array_keys( $this->conversionTable );

		$this->theme = $this->apiData->default_theme;
		$this->getCurrentTheme();
		return;
	}

	public function getCurrentTheme( ): string {
		if ( isset( $_COOKIE["theme"] ) ) {
			$this->setTheme( $_COOKIE["theme"] );
		}
		return $this->theme;
	}

	public function getInverseTheme( ): string {
		return $this->conversionTable[ $this->theme ];
	}

	public function setTheme( $selectedTheme ): void {
		if ( in_array( $selectedTheme, $this->validThemes ) ) {
			$this->theme = $selectedTheme;
			setcookie( "theme", $this->theme, time() + (86400 * 30), "/" );
		}
		return;
	}

	public function changeTheme( ): void {
		$this->theme = $this->conversionTable[ $this->theme ];
		$this->setTheme( $this->theme );
		return;
	}	

}