<?php

namespace Digitalkreativ\SimpleGifTransitions;

use Digitalkreativ\SimpleGifTransitions\Library\Config;
use Digitalkreativ\SimpleGifTransitions\Scenarios\BaseScenario;
use Intervention\Image\ImageManager;

/**
 * Class GifGenerator
 *
 * @package Digitalkreativ\SimpleGifTransitions
 */
class GifGenerator
{

    protected $_config;
    protected $_fromImage;
    protected $_toImage;

    protected $_width;
    protected $_height;

    protected $_scenario;

    protected $_interventionManager;


    public function __construct( Config $config=null, BaseScenario $scenario=null )
    {
        if( !is_null( $config ) ){
            $this->_config = $config;
        }

        if( !is_null( $scenario ) ){
            $this->_scenario = $scenario;
        }
    }

    public function generate( $filePath )
    {

        if( is_null( $this->_scenario ) ){
            throw new \Exception('Scenario not set');
        } elseif( !is_file( $this->_fromImage ) ){
            throw new \Exception('From image not set');
        } elseif( !is_file( $this->_toImage ) ){
            throw new \Exception('To Image not set');
        }

        $this->_interventionManager = new ImageManager( array('driver' =>  $this->_config->getImageDriver() ) );

        $defaultTempDirectory =  $this->_config->getTempDirectory();
        $this->_config->setTempDirectory( $this->_getNewTempDirectoryPath( $this->_config->getTempDirectory() ) );

        $framesDirectoryPath = $this->_config->getTempDirectory();

        $numberOfFrames = $this->_scenario->frames( $this->_interventionManager, $this->_config, $this->_fromImage, $this->_toImage, $this->_width, $this->_height );


        if( $this->_config->isDebug() ){
            echo 'Frames stored in: ' . $framesDirectoryPath . PHP_EOL;
            echo 'Number of frames: ' . $numberOfFrames . PHP_EOL;
        }

        $gif = new \ErikvdVen\Gif\GIFGenerator();

        $imageFrames = [];
        $imageFrames['repeat'] = false;
        $imageFrames['frames'] = [];

        $files = glob( $framesDirectoryPath . '/*.jpg');

        foreach( $files as $file ){
            $imageFrames['frames'][] = [
                'image' => $file,
                'delay' => 5
            ];
        }

        if( $this->_config->isDebug() ){
            print_r( $imageFrames );
            echo PHP_EOL;
        }

        file_put_contents( $filePath , $gif->generate($imageFrames) );

        if( $this->_config->isDebug() ){
            echo "created " . $filePath . PHP_EOL;
        }

        // Clean up after our selves
        $this->_deleteFramesDirectory( $framesDirectoryPath );

        // Restore the original directory
        $this->_config->setTempDirectory( $defaultTempDirectory );

        return true;
    }


    /*
     |---------------------------------
     | GETTERS AND SETTERS
     |---------------------------------
     */

    public function getConfig()
    {
        return $this->_config;
    }

    public function setConfig( Config $config)
    {
        $this->_config = $config;
        return $this;
    }

    public function getFromImage()
    {
        return $this->_fromImage;
    }

    public function setFromImage($fromImage)
    {
        if( !is_file( $fromImage ) ){
            throw new \Exception('File ' . $fromImage . ' does not exist');
        }
        $this->_fromImage = $fromImage;

        $this->_setWidthAndHeight( $fromImage );

        return $this;
    }

    public function getToImage()
    {
        return $this->_toImage;
    }

    public function setToImage($toImage)
    {
        if( !is_file( $toImage ) ){
            throw new \Exception('File ' . $toImage . ' does not exist');
        }

        $this->_toImage = $toImage;

        $this->_setWidthAndHeight( $toImage );

        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }

    public function getScenario()
    {
        return $this->_scenario;
    }

    public function setScenario( BaseScenario $scenario)
    {
        $this->_scenario = $scenario;
        return $this;
    }


    protected function _setWidthAndHeight( $imagePath )
    {
        $size = getimagesize( $imagePath );

        if( $size === false ){
            throw new \Exception('Not a valid image');
        }

        $currentWidth = (int) $size[0];
        $currentHeight = (int) $size[1];

        if( empty( $this->_width ) or empty( $this->_height ) ){

            $this->_width = (int) $currentWidth;
            $this->_height = (int) $currentHeight;

        } else {

            if( (int) $currentWidth != (int) $this->_width ){
                throw new \Exception('Image width not correct, should be ' . $this->_width. ' but is ' . $currentWidth );
            } elseif( (int) $currentHeight != (int) $this->_height ){
                throw new \Exception('Image height not correct, should be ' . $this->_height. ' but is ' . $currentHeight );
            }

        }
    }

    protected function _getImage( $imageName )
    {

        if( $imageName == 'toImage'){
            return $this->_toImage;
        } elseif( $imageName == 'fromImage'){
            return $this->_fromImage;
        }

    }


    protected function _getNewTempDirectoryPath( $directoryPath )
    {
        if( substr( strrev( $directoryPath ), 0, 1 ) != DIRECTORY_SEPARATOR ){
            $directoryPath.=  DIRECTORY_SEPARATOR;
        }

        $directoryPath.= md5( time() );

        mkdir( $directoryPath );

        return $directoryPath;
    }

    protected function _deleteFramesDirectory( $directoryPath )
    {
        if( $directoryPath != $this->_config->getTempDirectory() ){
            throw new \Exception('Oops');
        }

        $files = glob( $directoryPath . '/*.jpg');

        foreach( $files as $file ){
            unlink( $file );
        }

        rmdir( $directoryPath );
    }

}