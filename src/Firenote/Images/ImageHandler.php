<?php

namespace Firenote\Images;

use Firenote\Configuration;
use Imagine\Image\ImagineInterface;
use Firenote\Exceptions\Images\InvalidSizeFormat;
use Firenote\Exceptions\Images\InvalidTransformation;
use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;
use Imagine\Filter\Transformation;

class ImageHandler
{
    const
        SIZE_DELIMITER = 'x';
    
    private
        $configuration,
        $formats,
        $imagine,
        $storageDir,
        $filters;
    
    public function __construct(Configuration $configuration, ImagineInterface $imagine, $storageDir)
    {
        $this->configuration = $configuration;
        $this->formats = $configuration->read('images/formats', array());
        $this->imagine = $imagine;
        $this->storageDir = rtrim($storageDir, '/') . '/';
        
        $this->initializeFilters();
    }
    
    private function initializeFilters()
    {
        $this->filters = array(

            'resize' => function(Transformation $transformation, $value) {
                list($width, $height) = $this->translateSizeString($value);
                $transformation->resize(new \Imagine\Image\Box($width, $height));
            },
            
            'scale' => function(Transformation $transformation, $value) {
                list($width, $height) = $this->translateSizeString($value);
                $transformation->thumbnail(new \Imagine\Image\Box($width, $height), ImageInterface::THUMBNAIL_INSET);
            },
        );
    }
    
    public function applyFormat($imagePath, $format)
    {
        // FIXME
        $relativeImagePath = ltrim($imagePath, '/');
        
        if(isset($this->formats[$format]) && is_file($relativeImagePath))
        {
            try
            {
                return $this->getFormat($relativeImagePath, $format);
            }
            catch(\Exception $e)
            {
                // no transformation
            }
        }
        
        return $imagePath;
    }
    
    private function getFormat($imagePath, $format)
    {
        $targetPath = $this->computePath($imagePath, $format);
        
        if(! is_file($targetPath))
        {
            $this->applyTransformation($imagePath, $targetPath, $format);
        }
        
        return $targetPath;
    }
    
    private function computePath($imagePath, $format)
    {
        $targetDirectory = $this->storageDir . md5($imagePath);
        $this->ensureDirectoryExists($targetDirectory);
        
        $fileInfo = pathinfo($imagePath);
        
        return sprintf(
            '%s/%s.%s',
            $targetDirectory,
            $format,
            $fileInfo['extension']
        );
    }
    
    private function ensureDirectoryExists($directory)
    {
        if(!is_dir($directory))
        {
            if(!mkdir($directory, 0755, true))
            {
                throw new \Firenote\Exceptions\Filesystem("Cannot create directory $directory");
            }
        }
    }
    
    private function applyTransformation($imageSourcePath, $imageTargetPath, $format)
    {
        $transformation = $this->getTransformation($format);
        $transformation->save($imageTargetPath);
        
        $transformation->apply($this->imagine->open($imageSourcePath));
    }
    
    private function getTransformation($format)
    {
        $transformation = new Transformation();
        
        foreach($this->formats[$format] as $action => $value)
        {
            if(!isset($this->filters[$action]))
            {
                throw new InvalidTransformationFilter($action);
            }
            
            $this->filters[$action]($transformation, $value);
        }
        
        return $transformation;
    }
    
    private function translateSizeString($string)
    {
        if(strpos($string, self::SIZE_DELIMITER) === false)
        {
            throw new InvalidSizeFormat($string);
        }
           
        return explode(self::SIZE_DELIMITER, $string);
    }
}