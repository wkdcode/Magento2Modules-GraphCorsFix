<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Stratus21\GraphCorsFix\Model\Query;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use Stratus21\Core\Notify\Responder;

/**
 * Wrapper for GraphQl query parser. It parses query string into a `GraphQL\Language\AST\DocumentNode`
 */
class QueryParser extends \Magento\Framework\GraphQl\Query\QueryParser
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


    // public function parse(string $query): DocumentNode
    // {
    //     $cacheKey = sha1($query);
    //     $this->responder->log('LIAM','info', 'query - ' . $query);

    //     if (!isset($this->parsedQueries[$cacheKey])) {

    //         $this->responder->log('LIAM','info', 'inside isset');

    //         $this->parsedQueries[$cacheKey] = !empty($query) ? Parser::parse(new Source($query, 'GraphQL')) : '';

    //         $this->responder->log('LIAM','info', 'after inside isset');

    //     }

    //     $this->responder->log('LIAM','info', 'return now');

    //     return $this->parsedQueries[$cacheKey];
    // }

    /**
     * Parse query string into a `GraphQL\Language\AST\DocumentNode`.
     *
     * @param string $query
     * @return DocumentNode
     * @throws \GraphQL\Error\SyntaxError
     */
    public function parse(string $query): DocumentNode
    {
        $cacheKey = sha1($query);

        $this->responder->log('LIAM','info', 'query - ' . $query);

        if (!isset($this->parsedQueries[$cacheKey])) {
            if (!empty($query)) {
                 $this->responder->log('LIAM','info', 'if not empty');

                $this->parsedQueries[$cacheKey] = Parser::parse(new Source($query, 'GraphQL'));
            } else {
                $this->responder->log('LIAM','info', 'parse - if empty definitions before');
                
                $fallbackQuery = '{ __typename }';

                $this->parsedQueries[$cacheKey] = Parser::parse(new Source($fallbackQuery, 'GraphQL'));

                $this->responder->log('LIAM','info', 'parse - if empty definitions after');

            }
        }

        $this->responder->log('LIAM','info', 'return now');

        return $this->parsedQueries[$cacheKey];
    } 
}