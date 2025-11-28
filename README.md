# Docstron Laravel SDK

A Laravel SDK for the Docstron API, allowing you to easily generate PDFs, manage templates, applications, documents, and track usage statistics.

## Installation

You can install the package via composer:

```bash
composer require docstron/laravel-sdk
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=docstron-config
```

Add your API key to your `.env` file:

```env
DOCSTRON_API_KEY=your-api-key
DOCSTRON_BASE_URL=https://api.docstron.com/v1
```

## Usage

### Applications

```php
use Docstron\Laravel\Facades\Docstron;

// Get all applications
$apps = Docstron::getApplications();

// Get a specific application
$app = Docstron::getApplication('app-7b4d78fb-820c-4ca9-84cc-46953f211234');
```

### Usage Statistics

```php
use Docstron\Laravel\Facades\Docstron;

// Get usage statistics and limits
$usage = Docstron::getUsage();

// Response includes:
// - applications (total, limit, usage_percentage)
// - templates (total, limit, usage_percentage)
// - documents (total, monthly, usage, monthly_limit, usage_percentage)
// - subscription (plan_name, api_rate_limit, processing_priority, support_level)
```

### Templates

```php
use Docstron\Laravel\Facades\Docstron;

// Get all templates
$templates = Docstron::getTemplates();

// Create a template
$template = Docstron::createTemplate([
    'application_id' => 'app-7b4d78fb-820c-4ca9-84cc-46953f211234',
    'name' => 'Invoice Template',
    'content' => '<h1>Invoice for {{ customer_name }}</h1><p>Amount: {{ amount }}</p>',
    'is_active' => true,
    'extra_css' => '@page { margin: 3cm; }', // Optional
]);

// Get a specific template
$template = Docstron::getTemplate('template-c2465c0b-fc54-4672-b9ac-7446886cd6de');

// Update a template
$template = Docstron::updateTemplate('template-c2465c0b-fc54-4672-b9ac-7446886cd6de', [
    'name' => 'Updated Invoice Template',
    'is_active' => false,
]);

// Delete a template
Docstron::deleteTemplate('template-c2465c0b-fc54-4672-b9ac-7446886cd6de');
```

### Documents

#### Generate Document

```php
use Docstron\Laravel\Facades\Docstron;

// Generate a document with direct PDF response
$pdfContent = Docstron::generateDocument([
    'template_id' => 'template-c2465c0b-fc54-4672-b9ac-7446886cd6de',
    'data' => [
        'customer_name' => 'John Doe',
        'amount' => '$299.00',
    ],
    'response_type' => 'pdf', // Default
]);

// Save the PDF to file
file_put_contents('invoice.pdf', $pdfContent);

// Generate with Base64 response
$response = Docstron::generateDocument([
    'template_id' => 'template-c2465c0b-fc54-4672-b9ac-7446886cd6de',
    'data' => [
        'customer_name' => 'John Doe',
        'amount' => '$299.00',
    ],
    'response_type' => 'json_with_base64',
]);

// Generate with Document ID response
$response = Docstron::generateDocument([
    'template_id' => 'template-c2465c0b-fc54-4672-b9ac-7446886cd6de',
    'data' => [
        'customer_name' => 'John Doe',
        'amount' => '$299.00',
    ],
    'response_type' => 'document_id',
]);

// Generate password-protected PDF
$pdfContent = Docstron::generateDocument([
    'template_id' => 'template-c2465c0b-fc54-4672-b9ac-7446886cd6de',
    'data' => [
        'customer_name' => 'John Doe',
        'amount' => '$299.00',
    ],
    'response_type' => 'pdf',
    'password' => 'SecureDoc2025!',
]);
```

#### Quick Generate Document

```php
use Docstron\Laravel\Facades\Docstron;

// Quick generate without pre-creating a template
$pdfContent = Docstron::quickGenerateDocument([
    'html_content' => '<h1>Hello {{ name }}</h1><p>Welcome to Docstron!</p>',
    'data' => [
        'name' => 'John Doe',
    ],
    'response_type' => 'pdf',
]);

// Quick generate with custom CSS
$pdfContent = Docstron::quickGenerateDocument([
    'html_content' => '<h1>Invoice</h1>',
    'extra_css' => '@page { margin: 2cm; }',
    'response_type' => 'pdf',
]);

// Quick generate and save as template
$response = Docstron::quickGenerateDocument([
    'html_content' => '<h1>Receipt</h1>',
    'save_as_template' => true,
    'template_name' => 'Receipt Template',
    'application_id' => 'app-7b4d78fb-820c-4ca9-84cc-46953f211234',
    'response_type' => 'document_id',
]);
```

#### Manage Documents

```php
use Docstron\Laravel\Facades\Docstron;

// Get all documents
$documents = Docstron::getDocuments();

// Get a specific document
$document = Docstron::getDocument('document-489a79af-8680-4a08-a777-df52f26f296f');

// Download a document
$pdfContent = Docstron::downloadDocument('document-489a79af-8680-4a08-a777-df52f26f296f');
file_put_contents('downloaded.pdf', $pdfContent);

// Update a document
$document = Docstron::updateDocument('document-489a79af-8680-4a08-a777-df52f26f296f', [
    'attributes' => [
        'customer_name' => 'Jane Doe',
        'amount' => '$399.00',
    ],
]);

// Delete a document
Docstron::deleteDocument('document-489a79af-8680-4a08-a777-df52f26f296f');
```

## Response Types

When generating documents, you can specify different response types:

- **`pdf`** (default): Returns the PDF file content directly as binary data
- **`json_with_base64`**: Returns JSON with the PDF encoded as base64
- **`document_id`**: Returns JSON with the document ID for later retrieval

## Error Handling

The SDK throws `GuzzleException` for HTTP errors. You should wrap your calls in try-catch blocks:

```php
use GuzzleHttp\Exception\GuzzleException;
use Docstron\Laravel\Facades\Docstron;

try {
    $pdf = Docstron::generateDocument([
        'template_id' => 'template-id',
        'data' => ['name' => 'John'],
    ]);
} catch (GuzzleException $e) {
    // Handle error
    logger()->error('Docstron API Error: ' . $e->getMessage());
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
