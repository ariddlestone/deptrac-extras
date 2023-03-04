<?php

namespace ARiddlestone\DeptracExtras\Tests\Layer\Collector;

use ARiddlestone\DeptracExtras\Layer\Collector\ClassLikeDocCollector;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\ClassLike;
use PHPUnit\Framework\TestCase;
use Qossmic\Deptrac\Core\Ast\AstMap\ClassLike\ClassLikeReference;
use Qossmic\Deptrac\Core\Ast\Parser\NikicPhpParser\NikicPhpParser;

class ClassLikeDocCollectorTest extends TestCase
{
    private NikicPhpParser $astParser;
    private ClassLikeDocCollector $collector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->astParser = $this->createMock(NikicPhpParser::class);

        $this->collector = new ClassLikeDocCollector($this->astParser);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     *
     * @covers \ARiddlestone\DeptracExtras\Layer\Collector\ClassLikeDocCollector::satisfy
     * @dataProvider provideSatisfy
     */
    public function testSatisfy(array $configuration, ?string $docComment, bool $expected): void
    {
        if ($docComment !== null) {
            $doc = $this->createMock(Doc::class);
            $doc->method('getReformattedText')->willReturn($docComment);
        } else {
            $doc = null;
        }

        $classLike = $this->createMock(ClassLike::class);
        $classLike->method('getDocComment')->willReturn($doc);

        $reference = $this->createMock(ClassLikeReference::class);

        $this->astParser->method('getNodeForClassLikeReference')
            ->with($reference)
            ->willReturn($classLike);
        $this->assertEquals($expected, $this->collector->satisfy($configuration, $reference));
    }

    static public function provideSatisfy(): iterable
    {
        yield [
            ['value' => '@package MyPackage'],
            '@package MyPackage',
            true,
        ];

        yield [
            ['value' => '@package MyPackage'],
            '@package SomeOtherPackage',
            false,
        ];

        yield [
            ['value' => '@package MyPackage'],
            null,
            false,
        ];

        yield [
            ['value' => '@package\s+MyPackage$'],
            '@package MyPackage',
            true,
        ];

        yield [
            ['value' => '@package\s+MyPackage$'],
            "\n@package MyPackage\n",
            true,
        ];
    }
}
