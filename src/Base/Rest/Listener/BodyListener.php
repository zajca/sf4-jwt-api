<?php declare(strict_types=1);

namespace App\Base\Rest\Listener;

use App\Base\Rest\Serializer\Normalizer\JsonToFormNormalizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpKernel\KernelEvents;


class BodyListener implements EventSubscriberInterface
{
    
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 30],
        ];
    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        $method = $request->getMethod();
        $contentType = $request->headers->get('Content-Type');
        
        if ($this->isDecodeable($request)) {
            $format = null === $contentType
                ? $request->getRequestFormat()
                : $request->getFormat($contentType);
            
            $content = $request->getContent();
            
            if ($format !== 'json') {
                if ($this->isNotAnEmptyDeleteRequestWithNoSetContentType($method, $content, $contentType)) {
                    throw new UnsupportedMediaTypeHttpException("Request body format '$format' not supported");
                }
                
                return;
            }
            
            if (!empty($content)) {
                
                $normalizer = new JsonToFormNormalizer;
                $data = $normalizer->normalize($content);
                if (\is_array($data)) {
                    $request->request = new ParameterBag($data);
                } else {
                    throw new BadRequestHttpException('Invalid ' . $format . ' message received');
                }
            }
        }
    }
    
    /**
     * Check if the Request is not a DELETE with no content and no Content-Type.
     *
     * @param $method
     * @param $content
     * @param $contentType
     *
     * @return bool
     */
    private function isNotAnEmptyDeleteRequestWithNoSetContentType($method, $content, $contentType)
    {
        return false === ('DELETE' === $method && empty($content) && empty($contentType));
    }
    
    /**
     * Check if we should try to decode the body.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function isDecodeable(Request $request)
    {
        if (!\in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }
        
        return !$this->isFormRequest($request);
    }
    
    /**
     * Check if the content type indicates a form submission.
     *
     * @param Request $request
     *
     * @return bool
     */
    private function isFormRequest(Request $request)
    {
        $contentTypeParts = explode(';', $request->headers->get('Content-Type'));
        
        if (isset($contentTypeParts[0])) {
            return in_array($contentTypeParts[0], ['multipart/form-data', 'application/x-www-form-urlencoded']);
        }
        
        return false;
    }
}
