<?php
namespace App\Services;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    public function directoryName($object, PropertyMapping $mapping): string
    {
        // Your custom logic to determine the directory name
        // For example, you can base it on some property of the $object
        if (method_exists($object, 'getDirectory')) {
            return $object->getDirectory() ?? $_ENV["TMP_PATH"];
        }

        // Default directory if no custom logic applies
        return $_ENV["TMP_PATH"];
    }
}