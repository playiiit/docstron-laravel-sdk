<?php

namespace Docstron\Laravel\Tests\Feature;

use Docstron\Laravel\Tests\TestCase;
use Docstron\Laravel\Facades\Docstron;
use Illuminate\Support\Facades\Http;

class DocstronTest extends TestCase
{
    public function test_docstron_facade_is_registered()
    {
        $this->assertTrue(app()->bound('docstron'));
    }

    public function test_docstron_config_is_loaded()
    {
        $this->assertEquals('https://api.docstron.com/v1', config('docstron.base_url'));
    }

    public function test_get_applications()
    {
        Http::fake([
            '*/applications' => Http::response(['data' => []], 200),
        ]);

        $response = Docstron::getApplications();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('data', $response);
    }

    public function test_get_application()
    {
        Http::fake([
            '*/applications/123' => Http::response(['id' => '123', 'name' => 'Test App'], 200),
        ]);

        $response = Docstron::getApplication('123');

        $this->assertEquals('123', $response['id']);
        $this->assertEquals('Test App', $response['name']);
    }

    public function test_get_usage()
    {
        Http::fake([
            '*/usage' => Http::response(['used' => 10, 'limit' => 100], 200),
        ]);

        $response = Docstron::getUsage();

        $this->assertEquals(10, $response['used']);
    }

    public function test_get_templates()
    {
        Http::fake([
            '*/templates' => Http::response(['data' => []], 200),
        ]);

        $response = Docstron::getTemplates();

        $this->assertIsArray($response);
    }

    public function test_create_template()
    {
        Http::fake([
            '*/templates' => Http::response(['id' => 'tpl_123'], 201),
        ]);

        $response = Docstron::createTemplate(['name' => 'New Template', 'content' => '<h1>Hello</h1>']);

        $this->assertEquals('tpl_123', $response['id']);
    }

    public function test_get_template()
    {
        Http::fake([
            '*/templates/tpl_123' => Http::response(['id' => 'tpl_123', 'name' => 'My Template'], 200),
        ]);

        $response = Docstron::getTemplate('tpl_123');

        $this->assertEquals('tpl_123', $response['id']);
    }

    public function test_update_template()
    {
        Http::fake([
            '*/templates/tpl_123' => Http::response(['id' => 'tpl_123', 'name' => 'Updated Template'], 200),
        ]);

        $response = Docstron::updateTemplate('tpl_123', ['name' => 'Updated Template']);

        $this->assertEquals('Updated Template', $response['name']);
    }

    public function test_delete_template()
    {
        Http::fake([
            '*/templates/tpl_123' => Http::response(['success' => true], 200),
        ]);

        $response = Docstron::deleteTemplate('tpl_123');

        $this->assertTrue($response['success']);
    }

    public function test_generate_document_json()
    {
        Http::fake([
            '*/documents/generate' => Http::response(['document_url' => 'http://example.com/doc.pdf'], 200),
        ]);

        $response = Docstron::generateDocument(['template_id' => 'tpl_123', 'data' => [], 'response_type' => 'json']);

        $this->assertEquals('http://example.com/doc.pdf', $response['document_url']);
    }

    public function test_generate_document_pdf()
    {
        Http::fake([
            '*/documents/generate' => Http::response('PDF_CONTENT_BINARY', 200),
        ]);

        $response = Docstron::generateDocument(['template_id' => 'tpl_123', 'data' => [], 'response_type' => 'pdf']);

        $this->assertEquals('PDF_CONTENT_BINARY', $response);
    }

    public function test_quick_generate_document_json()
    {
        Http::fake([
            '*/documents/quick-generate' => Http::response(['document_url' => 'http://example.com/doc.pdf'], 200),
        ]);

        $response = Docstron::quickGenerateDocument(['html' => '<h1>Test</h1>', 'response_type' => 'json']);

        $this->assertEquals('http://example.com/doc.pdf', $response['document_url']);
    }

    public function test_quick_generate_document_pdf()
    {
        Http::fake([
            '*/documents/quick-generate' => Http::response('PDF_CONTENT_BINARY', 200),
        ]);

        $response = Docstron::quickGenerateDocument(['html' => '<h1>Test</h1>', 'response_type' => 'pdf']);

        $this->assertEquals('PDF_CONTENT_BINARY', $response);
    }

    public function test_download_document()
    {
        Http::fake([
            '*/documents/download/doc_123' => Http::response('PDF_CONTENT_BINARY', 200),
        ]);

        $response = Docstron::downloadDocument('doc_123');

        $this->assertEquals('PDF_CONTENT_BINARY', $response);
    }

    public function test_get_documents()
    {
        Http::fake([
            '*/documents' => Http::response(['data' => []], 200),
        ]);

        $response = Docstron::getDocuments();

        $this->assertIsArray($response);
    }

    public function test_get_document()
    {
        Http::fake([
            '*/documents/doc_123' => Http::response(['id' => 'doc_123', 'status' => 'completed'], 200),
        ]);

        $response = Docstron::getDocument('doc_123');

        $this->assertEquals('doc_123', $response['id']);
    }

    public function test_update_document()
    {
        Http::fake([
            '*/documents/doc_123' => Http::response(['id' => 'doc_123', 'status' => 'archived'], 200),
        ]);

        $response = Docstron::updateDocument('doc_123', ['status' => 'archived']);

        $this->assertEquals('archived', $response['status']);
    }

    public function test_delete_document()
    {
        Http::fake([
            '*/documents/doc_123' => Http::response(['success' => true], 200),
        ]);

        $response = Docstron::deleteDocument('doc_123');

        $this->assertTrue($response['success']);
    }
}
