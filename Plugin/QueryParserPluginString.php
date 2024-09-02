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
     * Didnt need this in the end but left it here just incase
     * The idea was to return an empty string but we managed to return a blank fallback query!
     *
     * @param \Magento\Framework\GraphQl\Query\QueryParser $subject
     * @param callable $proceed
     * @param string $query
     *
     * @return void
     */
    public function aroundParse(QueryParser $subject, callable $proceed, string $query)
    {
        // Modify method behavior
        $result = $proceed($query);

        $this->responder->log('LIAM', 'info', 'aroundParse proceed has ran');
        
        if (empty($query)) {
            $this->responder->log('LIAM', 'info', 'Query is empty');
            // return '';
        }

        $this->responder->log('LIAM', 'info', 'aroundParse after empty query return');

        // Manipulate result if needed
        return $result;
    }
}
