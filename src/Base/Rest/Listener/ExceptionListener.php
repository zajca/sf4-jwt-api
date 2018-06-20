<?php declare(strict_types=1);

namespace App\Base\Rest\Listener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ExceptionListener implements EventSubscriberInterface
{
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    /**
     * @var TranslatorInterface
     */
    private $translator;
    
    public function __construct(SerializerInterface $serializer, TranslatorInterface $translator)
    {
        $this->serializer = $serializer;
        $this->translator = $translator;
    }
    
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        
        $exception = $event->getException();
        $message = $exception->getMessage();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $headers = [];
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers = $exception->getHeaders();
        }
        
        if ($message === null || $message === '') {
            $message = array_key_exists($statusCode, Response::$statusTexts) ? Response::$statusTexts[$statusCode] : 'error';
        }
        
        $data = [
            'code'    => $statusCode,
            'message' => $this->translator->trans($message, [], 'error'),
        ];
        
        $json = $this->serializer->serialize($data, 'json');
        $response = new JsonResponse(null, $statusCode, $headers);
        $response->setJson($json);
        $event->setResponse($response);
    }
}

