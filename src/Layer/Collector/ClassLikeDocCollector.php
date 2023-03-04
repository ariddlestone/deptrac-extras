<?php

namespace ARiddlestone\DeptracExtras\Layer\Collector;

use LogicException;
use Qossmic\Deptrac\Contract\Ast\TokenReferenceInterface;
use Qossmic\Deptrac\Contract\Layer\CollectorInterface;
use Qossmic\Deptrac\Core\Ast\AstMap\ClassLike\ClassLikeReference;
use Qossmic\Deptrac\Core\Ast\Parser\NikicPhpParser\NikicPhpParser;

class ClassLikeDocCollector implements CollectorInterface
{
    private NikicPhpParser $astParser;

    public function __construct(NikicPhpParser $astParser)
    {
        $this->astParser = $astParser;
    }

    public function satisfy(array $config, TokenReferenceInterface $reference): bool
    {
        if (! $reference instanceof ClassLikeReference) {
            return false;
        }

        return preg_match($this->getPattern($config), $this->getCommentDoc($reference));
    }

    private function getPattern(array $config): string
    {
        if (!isset($config['value']) || !is_string($config['value'])) {
            throw new LogicException('ClassLikeDocCollector needs the value configuration.');
        }

        return '/' . $config['value'] . '/im';
    }

    private function getCommentDoc(ClassLikeReference $reference): string
    {
        $node = $this->astParser->getNodeForClassLikeReference($reference);

        if ($node === null) {
            return '';
        }

        $doc = $node->getDocComment();

        if ($doc === null) {
            return '';
        }

        return $doc->getReformattedText();
    }
}
