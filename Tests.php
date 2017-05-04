<?php

require __DIR__ . '/Tree.php';

$root = new Node("машины");
$tree = new Tree($root);
$root->setName("автомобили");

assert($tree->getRoot()->getName() === "автомобили");

$ford = $tree->appendNode(new Node('Ford'), $root);
$mustang = $tree->appendNode(new Node("Mustang"), $ford);
$focus = $tree->appendNode(new Node("Focus"), $ford);

$vaz = $tree->appendNode(new Node('VAZ'), $root);
$xray = $tree->appendNode(new Node('XRay'), $vaz);
$kalina = $tree->appendNode(new Node('Kalina'), $vaz);

assert($tree->toJSON() === '{
	root : {
		name : "автомобили",
		childs : [
			{
				name : "Ford",
				childs : [
					{
						name : "Mustang",
						childs : []
					},
					{
						name : "Focus",
						childs : []
					}
				]
			},
			{
				name : "VAZ",
				childs : [
					{
						name : "XRay",
						childs : []
					},
					{
						name : "Kalina",
						childs : []
					}
				]
			}
		]
	}
}');

$tree->deleteNode($vaz);
$tree->deleteNode($focus);
$error;
try{
	$tree->deleteNode($xray);
} catch(NodeNotFoundException $e){
	$error = $e;
}
assert(isset($error));

unset($error);
try{
	$tree->appendNode($xray, $vaz);
} catch(ParentNotFoundException $e){
	$error = $e;
}
assert(isset($error));

assert($tree->getRoot()->getChildren()[0]->getChildren()[0]->getName() === "Mustang");
assert($ford->getParent()->getName() === "автомобили");

assert($tree->toJSON() === '{
	root : {
		name : "автомобили",
		childs : [
			{
				name : "Ford",
				childs : [
					{
						name : "Mustang",
						childs : []
					}
				]
			}
		]
	}
}');
?>