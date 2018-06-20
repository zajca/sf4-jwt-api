<?php
declare(strict_types=1);

namespace App\Base\Rest\Serializer\Normalizer;

/**
 * Stolen from FOSRestBundle
 * Should implement symfony NormalizerInterface but than it would be autoloaded to serializer
 */
class JsonToFormNormalizer
{
    
    /**
     * Makes data decoded from JSON application/x-www-form-encoded compliant.
     *
     * @param array $data
     */
    private function xWwwFormEncodedLike(&$data)
    {
        foreach ($data as $key => &$value) {
            if (\is_array($value)) {
                // Encode recursively
                $this->xWwwFormEncodedLike($value);
            } else {
                if (false === $value) {
                    // Checkbox-like behavior removes false data but PATCH HTTP method with just checkboxes does not work
                    $value = null;
                } else {
                    if (!\is_string($value)) {
                        // Convert everything to string
                        // true values will be converted to '1', this is the default checkbox behavior
                        $value = (string) $value;
                    }
                }
            }
        }
    }
    
    public function normalize($object)
    {
        $decodedData = @json_decode($object, true);
        if ($decodedData) {
            $this->xWwwFormEncodedLike($decodedData);
        }
        
        return $decodedData;
    }
}
