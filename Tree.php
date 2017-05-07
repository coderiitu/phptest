<?php

require __DIR__ . '/iTree.php';
require __DIR__ . '/Node.php';
require __DIR__ . '/ParentNotFoundException.php';
require __DIR__ . '/NodeNotFoundException.php';

/**
 *
 */
class Tree implements iTree
{
    /**
     * @var iNode
     */
    protected $root;

    /**
     * @param iNode $root
     */
    public function __construct(iNode $root)
    {
        $this->root = $root;
    }

    /**
     * @inheritdoc
     */
    public function getRoot(): ?iNode
    {
        return $this->root;
    }

    /**
     * @inheritdoc
     */
    public function getNode(string $nodeName): ?iNode
    {
        $result = null;

        $q = new SplQueue();

        if ($this->root) {
            $q->enqueue($this->root);
        }

        while ($q->count() > 0) {
            $node = $q->dequeue();

            if ($node->getName() === $nodeName) {
                $result = $node;
                break;
            }

            foreach ($node->getChildren() as $child) {
                if ($child->getName() === $nodeName) {
                    $result = $child;
                    break;
                }
                $q->enqueue($child);
            }
        }

        return $result;
    }

    /**
     * @param iNode $node
     */
    public function isIn(iNode $node)
    {
        if (!$this->root) {
            return false;
        }

        while ($node->getParent()) {
            $node = $node->getParent();
        }

        return $this->root->getName() === $node->getName();
    }

    /**
     * @inheritdoc
     */
    public function appendNode(iNode $node, iNode $parent): iNode
    {
        if (!$this->isIn($parent)) {
            throw new ParentNotFoundException();
        }

        $parent->addChild($node);

        $node->setParent($parent);

        return $node;
    }

    /**
     * @inheritdoc
     */
    public function deleteNode(iNode $node)
    {
        if (!$this->isIn($node)) {
            throw new NodeNotFoundException();
        } 

        $parent = $node->getParent();

        if (!$parent) {
            $this->root = null;
        } else {
            $parent->deleteChild($node->getName());
        }
    }

    /**
     * @inheritdoc
     */
    public function toJSON(): string
    {
        $json = '';

        $root = $this->getRoot();

        if ($root) {
            $rootJson = ltrim($root->toJSON(2), "\t");

            $json = <<<JSON
{
\troot : {$rootJson}
}
JSON;
        }

        $json = str_replace("\r\n", "\n", $json);

        return $json;
    }
}


if (__FILE__ != realpath($argv[0])) {
    return;
}

$root = new Node("машины");
$tree = new Tree($root);
$root->setName("автомобили");

$ford = $tree->appendNode(new Node('Ford'), $root);
$mustang = $tree->appendNode(new Node("Mustang"), $ford);
$focus = $tree->appendNode(new Node("Focus"), $ford);

$vaz = $tree->appendNode(new Node('VAZ'), $root);
$xray = $tree->appendNode(new Node('XRay'), $vaz);
$kalina = $tree->appendNode(new Node('Kalina'), $vaz);

print($tree->toJSON());
