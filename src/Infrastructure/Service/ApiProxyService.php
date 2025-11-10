<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Infrastructure\Service;

use Bro\WorldCoreBundle\Domain\Service\Interfaces\ApiProxyServiceInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function array_filter;
use function array_key_exists;
use function array_merge;
use function array_replace;
use function array_replace_recursive;
use function is_array;
use function is_string;
use function sprintf;

/**
 * Class ApiProxyService
 *
 * @package Bro\WorldCoreBundle\Infrastructure\Service
 */
readonly class ApiProxyService implements ApiProxyServiceInterface
{
    /**
     * @var array<string, string>
     */
    private array $baseUrls;

    /**
     * @var array<string, mixed>
     */
    private array $uploadDefaults;

    public function __construct(
        private HttpClientInterface $httpClient,
        array $baseUrls = [],
        array $uploadDefaults = [],
    ) {
        $this->baseUrls = $this->normalizeBaseUrls($baseUrls);
        $this->uploadDefaults = $this->resolveUploadDefaults($uploadDefaults);
    }

    /**
     *
     * @param string  $method
     * @param string  $type
     * @param Request $request
     * @param array   $body
     * @param string  $path
     *
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @return array
     */
    public function request(
        string $method,
        string $type,
        Request $request,
        array $body = [],
        string $path = '',
        array $options = [],
    ): array
    {
        $url = $this->buildUrl($type, $path);

        $response = $this->httpClient->request(
            $method,
            $url,
            $this->buildJsonOptions($request, $body, $options),
        );

        return $response->toArray();
    }

    public function requestFile(
        string $method,
        string $type,
        Request $request,
        array $body = [],
        string $path = '',
        array $uploadOptions = [],
    ): array
    {
        $url = $this->buildUrl($type, $path);

        $response = $this->httpClient->request(
            $method,
            $url,
            $this->buildMultipartOptions($request, $body, $uploadOptions),
        );

        return $response->toArray();
    }

    /**
     * @param array<string, mixed> $baseUrls
     *
     * @return array<string, string>
     */
    private function normalizeBaseUrls(array $baseUrls): array
    {
        $normalized = [];

        foreach ($baseUrls as $type => $baseUrl) {
            if (!is_string($type) || $type === '') {
                continue;
            }

            if (!is_string($baseUrl)) {
                continue;
            }

            $normalized[$type] = rtrim($baseUrl, '/');
        }

        return $normalized;
    }

    private function buildUrl(string $type, string $path): string
    {
        if (!array_key_exists($type, $this->baseUrls)) {
            throw new InvalidArgumentException(sprintf('Unknown API proxy type "%s".', $type));
        }

        $baseUrl = $this->baseUrls[$type];

        if ($baseUrl === '') {
            return $path;
        }

        if ($path === '') {
            return $baseUrl;
        }

        return $baseUrl . '/' . ltrim($path, '/');
    }

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function buildJsonOptions(Request $request, array $body, array $options): array
    {
        $options = $this->mergeHeaders($request, $options);

        if ($body !== []) {
            $options['json'] = $body;
        }

        return $this->filterEmptyOptions($options);
    }

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $uploadOptions
     *
     * @return array<string, mixed>
     */
    private function buildMultipartOptions(Request $request, array $body, array $uploadOptions): array
    {
        $resolved = $this->resolveUploadOptions($body, $uploadOptions);

        $files = $this->extractFiles($request, $resolved['files_parameter']);

        if ($files === []) {
            throw new InvalidArgumentException(
                sprintf('No uploaded files found for parameter "%s".', $resolved['files_parameter'])
            );
        }

        $formData = new FormDataPart(array_merge(
            $resolved['form_fields'],
            [$resolved['files_parameter'] => $files],
        ));

        $headers = array_replace(
            $formData->getPreparedHeaders()->toArray(),
            $this->buildHeaders($request, $resolved['headers'])
        );

        return $this->filterEmptyOptions([
            'headers' => $headers,
            'body' => $formData->bodyToString(),
        ]);
    }

    /**
     * @param array<string, mixed> $uploadDefaults
     *
     * @return array<string, mixed>
     */
    private function resolveUploadDefaults(array $uploadDefaults): array
    {
        $defaults = [
            'context_key_field' => 'contextKey',
            'context_value' => null,
            'context_id' => null,
            'workplace_id' => null,
            'media_folder' => null,
            'private' => true,
            'files_parameter' => 'files',
            'extra_fields' => [],
            'headers' => [],
        ];

        $resolved = array_replace($defaults, $uploadDefaults);

        if (!is_string($resolved['files_parameter']) || $resolved['files_parameter'] === '') {
            $resolved['files_parameter'] = $defaults['files_parameter'];
        }

        if (!is_string($resolved['context_key_field']) || $resolved['context_key_field'] === '') {
            $resolved['context_key_field'] = $defaults['context_key_field'];
        }

        $resolved['extra_fields'] = is_array($resolved['extra_fields']) ? $resolved['extra_fields'] : [];
        $resolved['headers'] = is_array($resolved['headers']) ? $resolved['headers'] : [];

        return $resolved;
    }

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $uploadOptions
     *
     * @return array{
     *     files_parameter: string,
     *     form_fields: array<string, mixed>,
     *     headers: array<string, string>,
     * }
     */
    private function resolveUploadOptions(array $body, array $uploadOptions): array
    {
        $resolved = array_replace_recursive($this->uploadDefaults, $uploadOptions);

        $filesParameter = $resolved['files_parameter'];

        if (!is_string($filesParameter) || $filesParameter === '') {
            $filesParameter = $this->uploadDefaults['files_parameter'];
        }

        $formFields = $this->buildUploadFormFields($body, $resolved);

        if (isset($resolved['extra_fields']) && is_array($resolved['extra_fields'])) {
            $formFields = array_merge($formFields, array_filter(
                $resolved['extra_fields'],
                static fn ($value) => $value !== null && $value !== ''
            ));
        }

        $headers = [];

        if (isset($resolved['headers']) && is_array($resolved['headers'])) {
            /** @var array<string, string> $headers */
            $headers = array_filter($resolved['headers'], static fn ($value) => $value !== null && $value !== '');
        }

        return [
            'files_parameter' => $filesParameter,
            'form_fields' => $formFields,
            'headers' => $headers,
        ];
    }

    /**
     * @param array<string, mixed> $body
     * @param array<string, mixed> $resolved
     *
     * @return array<string, mixed>
     */
    private function buildUploadFormFields(array $body, array $resolved): array
    {
        $contextKeyField = $resolved['context_key_field'];

        $contextValue = null;

        if (is_string($contextKeyField) && $contextKeyField !== '') {
            $contextValue = $body[$contextKeyField] ?? null;
        }

        if ($contextValue === null && isset($body['context'])) {
            $contextValue = $body['context'];
        }

        if ($contextValue === null) {
            $contextValue = $resolved['context_value'];
        }

        $mediaFolder = $body['mediaFolder'] ?? $resolved['media_folder'];

        if ($mediaFolder === null && isset($body['context'])) {
            $mediaFolder = $body['context'];
        }

        $fields = array_filter([
            $contextKeyField => $contextValue,
            'contextId' => $body['contextId'] ?? $resolved['context_id'],
            'workplaceId' => $body['workplaceId'] ?? $resolved['workplace_id'],
            'mediaFolder' => $mediaFolder,
        ], static fn ($value) => $value !== null && $value !== '');

        $fields['private'] = $this->normalizeBoolean($body['private'] ?? $resolved['private']);

        return $fields;
    }

    /**
     * @return array<int|string, DataPart>
     */
    private function extractFiles(Request $request, string $filesParameter): array
    {
        $files = $request->files->get($filesParameter);

        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        if (!is_array($files)) {
            return [];
        }

        $normalized = [];

        foreach ($files as $key => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $path = $file->getRealPath() ?: $file->getPathname();

            $normalized[$key] = DataPart::fromPath(
                $path,
                $file->getClientOriginalName(),
                $file->getMimeType() ?: 'application/octet-stream'
            );
        }

        return $normalized;
    }

    /**
     * @param array<string, string> $additionalHeaders
     *
     * @return array<string, string>
     */
    private function buildHeaders(Request $request, array $additionalHeaders = []): array
    {
        $headers = array_filter([
            'Authorization' => $request->headers->get('Authorization'),
        ], static fn ($value) => $value !== null && $value !== '');

        return array_replace($headers, $additionalHeaders);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function mergeHeaders(Request $request, array $options): array
    {
        $headers = [];

        if (isset($options['headers']) && is_array($options['headers'])) {
            $headers = $options['headers'];
        }

        $options['headers'] = $this->buildHeaders($request, $headers);

        if ($options['headers'] === []) {
            unset($options['headers']);
        }

        return $options;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function filterEmptyOptions(array $options): array
    {
        return array_filter(
            $options,
            static fn ($value) => $value !== null && $value !== [] && $value !== ''
        );
    }

    private function normalizeBoolean(mixed $value): string
    {
        return ($value === true || $value === '1' || $value === 1)
            ? '1'
            : '0';
    }
}
