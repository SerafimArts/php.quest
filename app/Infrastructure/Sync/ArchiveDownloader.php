<?php

declare(strict_types=1);

namespace App\Infrastructure\Sync;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ArchiveDownloader
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $temp,
        private readonly string $suffix = '.zip',
    ) {
    }

    private function createArchivePathname(): string
    {
        return $this->temp . '/' . \hash('xxh128', \random_bytes(32)) . $this->suffix;
    }

    /**
     * @param ResponseInterface $response
     * @param string $pathname
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    private function write(ResponseInterface $response, string $pathname): void
    {
        $pointer = @\fopen($pathname, 'w+');

        if ($pointer === false) {
            $message = 'An error occurred while writing into archive "%s": %s';
            $error = \error_get_last()['message'] ?? 'Unknown error';
            throw new \LogicException(\sprintf($message, $pathname, $error));
        }

        \flock($pointer, \LOCK_EX);

        foreach ($this->client->stream($response) as $chunk) {
            \fwrite($pointer, $chunk->getContent());
        }

        \flock($pointer, \LOCK_UN);
        \fclose($pointer);
    }

    /**
     * @param non-empty-string $url
     *
     * @return non-empty-string
     * @throws TransportExceptionInterface
     */
    public function download(string $url): string
    {
        $pathname = $this->createArchivePathname();

        $this->write($this->client->request('GET', $url), $pathname);

        return $pathname;
    }
}
