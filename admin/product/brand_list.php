<script language="JavaScript" src="../js/Tree_manager.js"></script>
<script language="JavaScript" src="../js/Tree.js"></script>

<script language="JavaScript">
<!--
function goUrl(url){
	document.location=url;
}

function onReload()
{
	//parent.frames[0].location.reload();
}

	/*	 Create Tree		*/
	var tree = new Tree();
	tree.color = "black";
	tree.bgColor = "white";
	tree.borderWidth = 0;


	/*	Create Root node	*/
	var rootnode = new TreeNode(" 최상위 ", "../image/tree/ServerMag_Etc_Root.gif","../image/tree/ServerMag_Etc_Root.gif");
	rootnode.action = "javascript:goUrl('prd_brand.php')";
	rootnode.expanded = true;	

	/*	Create Root node of Code	*/
	<?
	$sql = "select * from wiz_brand order by priorno asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($result)){

		$row->brdname .= "&nbsp;&nbsp;";
		
		if($row->idx == $idx) $row->brdname = "<b>".$row->brdname."</b>";
		
		echo "var node_$idx = new TreeNode(\"$row->brdname\",TREE_FOLDER_CLOSED_IMG,TREE_FOLDER_OPEN_IMG);\n";
		echo "node_$idx.expanded = true;\n";	
		echo "node_$idx.action = \"javascript:goUrl('prd_brand.php?mode=update&idx=$row->idx')\";\n";
		echo "rootnode.addNode(node_$idx);\n";

	}
	?>
	
	tree.addNode(rootnode);
//-->
</script>

<body>
<script>
//CodeMainMenu.draw();
//codeMenu.draw();
</script>

<div id=TREE_BAR style="margin:5;">
<script>		
tree.draw();
tree.nodes[0].select();
</script>
</div>