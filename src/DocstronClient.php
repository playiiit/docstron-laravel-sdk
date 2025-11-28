<?php

namespace Docstron\Laravel;

use Illuminate\Support\Facades\Http;

class DocstronClient
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct(string $apiKey, string $baseUrl)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Get Application details.
     *
     * @param string $appId
     * @return array
     */
    public function getApplication(string $appId)
    {
        return $this->request('GET', "applications/{$appId}");
    }

    /**
     * Get all applications.
     *
     * @return array
     */
    public function getApplications()
    {
        return $this->request('GET', 'applications');
    }

    /**
     * Get usage statistics and limits.
     *
     * @return array
     */
    public function getUsage()
    {
        return $this->request('GET', 'usage');
    }

    /**
     * Get all templates.
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->request('GET', 'templates');
    }

    /**
     * Create a new template.
     *
     * @param array $data
     * @return array
     */
    public function createTemplate(array $data)
    {
        return $this->request('POST', 'templates', $data);
    }

    /**
     * Get a specific template.
     *
     * @param string $templateId
     * @return array
     */
    public function getTemplate(string $templateId)
    {
        return $this->request('GET', "templates/{$templateId}");
    }

    /**
     * Update a template.
     *
     * @param string $templateId
     * @param array $data
     * @return array
     */
    public function updateTemplate(string $templateId, array $data)
    {
        return $this->request('PATCH', "templates/{$templateId}", $data);
    }

    /**
     * Delete a template.
     *
     * @param string $templateId
     * @return array
     */
    public function deleteTemplate(string $templateId)
    {
        return $this->request('DELETE', "templates/{$templateId}");
    }

    /**
     * Generate a document.
     *
     * @param array $data
     * @return mixed
     */
    public function generateDocument(array $data)
    {
        $responseType = $data['response_type'] ?? 'pdf';
        
        if ($responseType === 'pdf') {
            return $this->requestRaw('POST', 'documents/generate', $data);
        }

        return $this->request('POST', 'documents/generate', $data);
    }

    /**
     * Quick Generate a document.
     *
     * @param array $data
     * @return mixed
     */
    public function quickGenerateDocument(array $data)
    {
        $responseType = $data['response_type'] ?? 'pdf';

        if ($responseType === 'pdf') {
            return $this->requestRaw('POST', 'documents/quick-generate', $data);
        }

        return $this->request('POST', 'documents/quick-generate', $data);
    }

    /**
     * Download a document.
     *
     * @param string $documentId
     * @return mixed
     */
    public function downloadDocument(string $documentId)
    {
        return $this->requestRaw('GET', "documents/download/{$documentId}");
    }

    /**
     * Get a specific document.
     *
     * @param string $documentId
     * @return array
     */
    public function getDocument(string $documentId)
    {
        return $this->request('GET', "documents/{$documentId}");
    }

    /**
     * Get all documents.
     *
     * @return array
     */
    public function getDocuments()
    {
        return $this->request('GET', 'documents');
    }

    /**
     * Update a document.
     *
     * @param string $documentId
     * @param array $data
     * @return array
     */
    public function updateDocument(string $documentId, array $data)
    {
        return $this->request('PATCH', "documents/{$documentId}", $data);
    }

    /**
     * Delete a document.
     *
     * @param string $documentId
     * @return array
     */
    public function deleteDocument(string $documentId)
    {
        return $this->request('DELETE', "documents/{$documentId}");
    }

    /**
     * Make a JSON request.
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return array
     */
    protected function request(string $method, string $uri, array $data = [])
    {
        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->send($method, $uri, [
                'json' => $data,
            ]);

        return $response->json();
    }

    /**
     * Make a raw request (for PDF downloads).
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return string
     */
    protected function requestRaw(string $method, string $uri, array $data = [])
    {
        $response = Http::withToken($this->apiKey)
            ->baseUrl($this->baseUrl)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->send($method, $uri, [
                'json' => $data,
            ]);

        return $response->body();
    }
}
