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


    public function aroundParse(QueryParser $subject, callable $proceed, string $query)
    {
        // Modify method behavior
        $result = $proceed($query);

        $this->responder->log('LIAM', 'info', 'aroundParse proceed has ran');
        
        if (empty($query)) {
            return '';
        }

        $this->responder->log('LIAM', 'info', 'aroundParse after empty query return');

        // Manipulate result if needed
        return $result;
    }

    // public function afterParse(QueryParser $subject, DocumentNode $result, string $query)
    // {
    //      // Log to check if the plugin is working
    //      $this->responder->log('LIAM', 'info', 'afterParse working');

    //      // Check if the query was empty (fall back to minimal query) and if the DocumentNode's definitions are empty
    //      if (empty($query)) {
    //          // Log the condition
    //          $this->responder->log('LIAM', 'info', 'Empty query and empty definitions, returning an empty string');
 
    //          // Return an empty string
    //          return '';
    //      }
 
    //      // Log the result before returning
    //      $this->responder->log('LIAM', 'info', 'Returning original DocumentNode');
 
    //      // Otherwise, return the DocumentNode as is
    //      return $result;
    // }
}
