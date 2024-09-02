<?php

namespace Stratus21\GraphCorsFix\Plugin;

use GraphQL\Language\AST\DocumentNode;
use Magento\Framework\GraphQl\Query\QueryParser;
use Stratus21\Core\Notify\Responder;

class QueryParserPluginString
{
    /**
     * @var \Stratus21\Core\Notify\Responder
     */ 
    protected $responder;

    /**
     * Constructor
     *
     * @param Responder $logger
     */
    public function __construct(Responder $responder)
    {
        $this->responder            = $responder;
    }

    /**
     * After plugin to modify the result of the parse method
     *
     * @param QueryParser $subject
     * @param DocumentNode $result
     * @param string $query
     * @return DocumentNode|string
     */
    public function afterParse(QueryParser $subject, DocumentNode $result, string $query)
    {
         // Log to check if the plugin is working
         $subject->responder->log('LIAM', 'info', 'afterParse working');

         // Check if the query was empty (fall back to minimal query) and if the DocumentNode's definitions are empty
         if (empty($query)) {
             // Log the condition
             $subject->responder->log('LIAM', 'info', 'Empty query and empty definitions, returning an empty string');
 
             // Return an empty string
             return '';
         }
 
         // Log the result before returning
         $subject->responder->log('LIAM', 'info', 'Returning original DocumentNode');
 
         // Otherwise, return the DocumentNode as is
         return $result;
    }
}
