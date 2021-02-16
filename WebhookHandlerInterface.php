<?php

namespace DNAFactory\Teamwork;

use DNAFactory\Teamwork\Models\Ticket;

interface WebhookHandlerInterface
{
    public function handle(string $type, array $rawData);
}