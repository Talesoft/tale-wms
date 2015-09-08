<?php

namespace Lvs\Controllers;

use Lvs\ControllerBase,
	Lvs\ClassLoader,
	Lvs\Form;

class ScanCodeController extends ControllerBase {

	public function init() {
		parent::init();

		$this->recacheTime = 3600;

		$this->cachePath = $this->app->getCachePath().'/barcodes';

		if( !is_dir( $this->cachePath ) ) {

			mkdir( $this->cachePath, 0777, true );
		}

		//Lets always do some cache cleaning here, please
		$cachedFiles = glob( $this->cachePath.'/*.png' );

		foreach( $cachedFiles as $file )
			if( is_file( $file ) && time() - filemtime( $file ) > $this->recacheTime )
				unlink( $file );


		$this->loaders = [
			//Fuckin learn something about naming conventions, bitch!
			new ClassLoader( \Lvs\LIBRARY_PATH.'/Barcode', null, '%s.barcode.php' ),
			new ClassLoader( \Lvs\LIBRARY_PATH.'/Barcode', null, '%s.php' )
		];
		
		foreach( $this->loaders as $l )
			$l->register();

		$this->form = new Form( [
			'labelStyle' => 'normal'
		], Form::METHOD_GET );
	}

	public function code128Action( $text = null ) {

		$text = $text ? $text : '000-000-000';

		if( $this->format !== 'png' )
			return $this->redirect( "scan-code/code-128/{$text}.png", true );

		$labelStyle = $this->form->labelStyle->getString();

		if( !in_array( $labelStyle, [ 'normal', 'none' ] ) )
			$labelStyle = 'normal';

		$fileName = md5( $text.$labelStyle ).'.png';
		$path = "{$this->cachePath}/$fileName";

		if( file_exists( $path ) && time() - filemtime( $path ) < $this->recacheTime )
			return file_get_contents( $path );

		$font = new \BCGFontFile( \Lvs\ROOT_PATH.'/fonts/Arial.ttf', 14 );
		$black = new \BCGColor( 0, 0, 0 );
		$white = new \BCGColor( 255, 255, 255 );

		$ex = null;
		try {

			$code = new \BCGcode128();
			$code->setScale( 3 );
			$code->setThickness( 30 );
			$code->setForegroundColor( $black );
			$code->setBackgroundColor( $white );
			$code->setFont( $font );

			if( $labelStyle === 'none' )
				$code->setLabel( null );
			
			$code->parse( $text );

		} catch( \Exception $e ) {

			$ex = $e;
		}


		
		$img = new \BCGDrawing( $path, $white );
		
		if( $ex )
			$img->drawException( $ex );
		else {

			$img->setBarcode( $code );
			$img->draw();
		}

		$img->finish( \BCGDrawing::IMG_FORMAT_PNG );

		if( !file_exists( $path ) ) {

			$img->setFilename( null );
			$img->draw();
			$img->finish( \BCGDrawing::IMG_FORMAT_PNG );

			return '';
		}

		return file_get_contents( $path );
	}

	public function authCodeAction() {

		$code = null;
		do {

			$code = 'LVS-AUTH-'.str_pad( rand( 0, 999999 ), 6, 0, \STR_PAD_LEFT );
		} while( $this->db->hasUserByScanCode( $code ) );

		return $this->redirect( "scan-code/code-128/{$code}.png", true );
	}

	public function storageCodeAction() {

		$code = null;
		do {

			$code = 'LVS-STOR-'.str_pad( rand( 0, 999999 ), 6, 0, \STR_PAD_LEFT );
		} while( $this->db->hasStorageByScanCode( $code ) );

		return $this->redirect( "scan-code/code-128/{$code}.png", true );
	}

	public function productCodeAction() {

		$code = null;
		do {

			$code = 'LVS-ITEM-'.str_pad( rand( 0, 999999 ), 6, 0, \STR_PAD_LEFT );
		} while( $this->db->hasProductByScanCode( $code ) );

		return $this->redirect( "scan-code/code-128/{$code}.png", true );
	}

	public function commandCodeAction( $command = null ) {

		$command = $command ? strtoupper( $command ) : 'UNDEFINED';

		return $this->redirect( "scan-code/code-128/LVS-CMD-{$command}.png", true );
	}
}