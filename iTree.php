<?php
interface iTree {
	/*
		@return Node корневой лист дерева, NULL, если нет
	*/
	function getRoot(): ?iNode;

	/*
		Достает лист из дерева
		@params string nodeName имя листа для поиска
		@return Node лист с заданным именем, NULL если такого листа нет в дереве	
	*/
	function getNode(string $nodeName): ?iNode;
	
	/*
		Добавляет лист к листу $parent
		@param Node $node лист, который мы добавляем
		@param Node $parent лист-родителm, к которому добавляем
		@return Node лист, который добавили в дерево
		@throws ParentNotFoundException если ролитель не найдет в дереве
	*/
	function appendNode(iNode $node, iNode $parent): iNode;
	
	/*
		Удаляет лист и всех детей рекурсивно
		@param Node $node лист для удаления
		@throws NodeNotFoundException такой лист не найдет в дереве 
	*/
	function deleteNode(iNode $node);

	 /*
		@return string json представление дерева, вида 
		{ root : { 
				name : "rootNodeName", 
				childs : [
					{
						name : "childOne",
						childs : []
					},
					{
						name : "childTwo",
						childs : []
					}
				]
			}
		}
	 */
	function toJSON(): string;
}
?>