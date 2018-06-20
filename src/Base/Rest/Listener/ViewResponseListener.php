<?php declare(strict_types=1);

namespace App\Base\Rest\Listener;


use App\Base\Rest\View;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ViewResponseListener implements EventSubscriberInterface
{
    
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 30],
        ];
    }
    
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        if ($result instanceof View) {
            $json = $this->serializer->serialize($result->getData(), $result->getFormat(), $result->getContext());
            $response = new JsonResponse(null, $result->getStatusCode(), $result->getHeaders());
            $response->setJson($json);
            $event->setControllerResult($response);
            $event->setResponse($response);
        }
    }
}
