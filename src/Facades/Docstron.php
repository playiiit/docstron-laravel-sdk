<?php

namespace Docstron\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getApplication(string $appId)
 * @method static array getApplications()
 * @method static array getUsage()
 * @method static array getTemplates()
 * @method static array createTemplate(array $data)
 * @method static array getTemplate(string $templateId)
 * @method static array updateTemplate(string $templateId, array $data)
 * @method static array deleteTemplate(string $templateId)
 * @method static mixed generateDocument(array $data)
 * @method static mixed quickGenerateDocument(array $data)
 * @method static mixed downloadDocument(string $documentId)
 * @method static array getDocument(string $documentId)
 * @method static array getDocuments()
 * @method static array updateDocument(string $documentId, array $data)
 * @method static array deleteDocument(string $documentId)
 * 
 * @see \Docstron\Laravel\DocstronClient
 */
class Docstron extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'docstron';
    }
}
