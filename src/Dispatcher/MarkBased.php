<?php
declare(strict_types=1);

namespace FastRoute\Dispatcher;

use FastRoute\Dispatcher\Result\Matched;

use function preg_match;

class MarkBased extends RegexBasedAbstract
{
    /** @inheritDoc */
    protected function dispatchVariableRoute(array $routeData, string $uri): ?Matched
    {
        foreach ($routeData as $data) {
            if (preg_match($data['regex'], $uri, $matches) !== 1) {
                continue;
            }

            [$handler, $varNames] = $data['routeMap'][$matches['MARK']];

            $vars = [];
            $i = 0;
            foreach ($varNames as $varName) {
                $vars[$varName] = $matches[++$i];
            }

            $result = new Matched();
            $result->handler = $handler;
            $result->variables = $vars;

            return $result;
        }

        return null;
    }
}
