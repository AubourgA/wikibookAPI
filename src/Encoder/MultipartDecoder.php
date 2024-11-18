<?php

namespace App\Encoder;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function decode(string $data, string $format, array $context = []): ?array
    {
        
        $request = $this->requestStack->getCurrentRequest();
      
        if (!$request) {
            return null;
        }
        
         $decodedData = array_map(static function (string $element) {
           
            $decoded = json_decode($element, true);
            
            return \is_array($decoded) ? $decoded : $element;
        }, $request->request->all()) + $request->files->all();
        
        
        if (isset($decodedData['nbPage'])) {
            $decodedData['nbPage'] = (int) $decodedData['nbPage'];
        }
        if (isset($decodedData['YearPublished'])) {
            $decodedData['YearPublished'] = (int) $decodedData['YearPublished'];
        }
        if (isset($decodedData['isOnLine'])) {
            $decodedData['isOnLine'] = (bool) $decodedData['isOnLine'];
        }
      
        return $decodedData;
    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}