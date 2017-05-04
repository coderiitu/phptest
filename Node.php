<?php

require __DIR__ . '/iNode.php';

/**
 *
 */
class Node implements iNode
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var iNode[]
     */
    protected $children = [];

    /**
     * @var iNode
     */
    protected $parent;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @inheritdoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function addChild(iNode $node)
    {
        $this->children[] = $node;
    }

    /**
     *
     */
    public function deleteChild(string $nodeName)
    {
        foreach ($this->children as $i => $child) {
            if ($child->getName() === $nodeName) {
                unset($this->children[$i]);
                break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getParent(): ?iNode
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function setParent(iNode $parent)
    {
        $this->parent = $parent;
    }

    /**
     *
     */
    public function __toString()
    {
        $result = [];

        $result[] = sprintf('name: %s', $this->name ?: 'null');

        $result[] = sprintf('parent: %s', $this->parent ? ($this->parent->getName() ?: 'null') : 'null');

        foreach ($this->children as $child) {
            $result[] = sprintf('child: %s', $child ? ($child->getName() ?: 'null') : 'null');
        }

        return implode(PHP_EOL, $result);
    }

    /**
     * @return string
     */
    protected function getTabs($n): string
    {
        $result = "";

        for ($i = 0; $i < $n; $i ++) {
            $result .= "\t";
        }

        return $result;
    }

    /**
     * @return string
     */
    public function toJSON($depth = 1): string
    {
        $json = '';

        $bracketTabs = $this->getTabs($depth - 1);
        $tabs = $this->getTabs($depth);

        if (count($this->children) == 0) {
            $json = <<<JSON
{$bracketTabs}{
{$tabs}name : "{$this->name}",
{$tabs}childs : []
{$bracketTabs}}
JSON;
        } else {
            $childs = [];

            foreach ($this->children as $child) {
                $childs[] = $child->toJSON($depth + 2);
            }

            $strChilds = implode(",\n", $childs);

            $json = <<<JSON
{$bracketTabs}{
{$tabs}name : "{$this->name}",
{$tabs}childs : [
{$strChilds}
{$tabs}]
{$bracketTabs}}
JSON;
        }

        return $json;
    }
}


if (__FILE__ != realpath($argv[0])) {
    return;
}

$root = new Node('root');

$child_1 = new Node('child-1');
$child_1->setParent($root);

$child_2 = new Node('child-2');
$child_2->setParent($root);

$child_3 = new Node('child-3');
$child_3->setParent($child_2);

$root->addChild($child_1);
$root->addChild($child_2);
$child_2->addChild($child_3);

print($root->toJSON());
