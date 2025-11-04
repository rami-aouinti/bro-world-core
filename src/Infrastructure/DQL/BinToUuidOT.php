<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Infrastructure\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * @package Bro\WorldCoreBundle
 */
class BinToUuidOT extends FunctionNode
{
    public mixed $value;

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->value = $parser->StringPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'BIN_TO_UUID(' . $this->value->dispatch($sqlWalker) . ', 1)';
    }
}
