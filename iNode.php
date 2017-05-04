<?php 
/*
	Лист в дереве, имеет имя, может иметь лист-родиель и листы-детей.  
*/
interface iNode {
	/*
		 @return string имя листа, если есть, иначе NULL
	*/
	function getName(): ?string;

	/*
		Изменить имя листа
		@param string $name имя листа, если есть, иначе NULL
	*/
	function setName(string $name);

	/*
		@return array массив из Node которые являются дочерними по отношениею к текущему листу, иначе пустой массив
	*/
	function getChildren(): array;

	/*
		Добавляет дочерний лист
		@param iNode $child дочерний лист
	*/
	function addChild(iNode $child);

	/*
		@return Node родительский лист, если нет, то NULL
	*/
	function getParent(): ?iNode;

	/*
		Устанавливает лист-родитель
		@param iNode $parent лист родитель
	*/
	function setParent(iNode $parent);
} 
?>