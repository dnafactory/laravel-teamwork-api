<?php

namespace DNAFactory\Teamwork;

use DNAFactory\Teamwork\Exceptions\WebhookInvalidSignatureException;
use DNAFactory\Teamwork\Exceptions\WebhookUnhandledEventException;
use DNAFactory\Teamwork\Support\WebhookAdapter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Psr\Log\LoggerInterface;

class WebhookController extends Controller
{
    protected LoggerInterface $logger;
    protected string $secret;
    protected array $handlers;
    protected WebhookAdapter $webhookAdapter;

    public function __construct(
        LoggerInterface $logger,
        WebhookAdapter $webhookAdapter
    ) {
        $this->logger = $logger;
        $this->webhookAdapter = $webhookAdapter;
        $this->secret = config('teamwork.desk.webhook_secret');
        $this->handlers = config('teamwork.desk.webhook_handlers');
    }

    public function index(Request $request)
    {
        if (!$this->verifySignature($request)) {
            $message = 'TeamworkDesk Webhook request has an invalid signature.';
            $this->logger->alert($message, $request->toArray());
            throw new WebhookInvalidSignatureException($message);
        }
        $eventType = $request->header('X-Desk-Event', null);
        $handlerClass = $this->handlers[$eventType] ?? null;
        if (is_null($handlerClass)) {
            $message = "TeamworkDesk Webhook notifying event of type '$eventType' could not be handled.";
            $this->logger->alert($message, $request->toArray());
            throw new WebhookUnhandledEventException($message);
        }

        $this->logger->debug('test'.$handlerClass);
        $rawData = $request->json()->all();
        $handler = app($handlerClass);
        /** @var WebhookHandlerInterface $handler */
        return $handler->handle($eventType, $rawData);
    }

    protected function verifySignature(Request $request): bool
    {
        return true;
        $body = $request->getContent();
        $requestSignature = $request->header('X-Desk-Signature', null);
        if (!$requestSignature) {
            return false;
        }
        $signature = hash_hmac('sha256', $body, $this->secret);
        //$this->logger->debug('webhook signatures',[$signature, $requestSignature]);
        return $signature == $requestSignature;
    }
}
