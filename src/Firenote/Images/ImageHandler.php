<?php

namespace Firenote\Images;

use Firenote\Configuration;
use Imagine\Image\ImagineInterface;

class ImageHandler
{
    const
        SIZE_DELIMITER = 'x';
    
    private
        $configuration,
        $formats,
        $imagine,
        $storageDir;
    
    public function __construct(Configuration $configuration, ImagineInterface $imagine, $storageDir)
    {
        $this->configuration = $configuration;
        $this->formats = $configuration->read('images/formats', array());
        $this->imagine = $imagine;
        $this->storageDir = rtrim($storageDir, '/') . '/';
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
        $transformation = new \Imagine\Filter\Transformation();
        
        $formatDescription = $this->formats[$format];
        
        if(isset($formatDescription['resize']))
        {
            $sizeStr = $formatDescription['resize'];
            if(strpos($sizeStr, self::SIZE_DELIMITER) !== false)
            {
                $dimensions = explode(self::SIZE_DELIMITER, $sizeStr);
                $transformation->resize(new \Imagine\Image\Box($dimensions[0], $dimensions[1]));
            }
        }
        
        return $transformation;
    }
}