<?php
require_once ('../config/login.php');
require_once ('../config/functions.php');
require_once ('../config/db_connect.php');

$query = "SELECT COUNT(*) FROM tbl_news";
$query_result = mysql_query ($query)
  or die ("Невозможно сделать запрос");
$allrows = mysql_fetch_array ($query_result);

$deleteid = $_GET['deleteid'];

if ($_GET['deleteid']) {


	$query = "DELETE FROM tbl_news WHERE id=$deleteid";
	$query_result = mysql_query ($query) or die (mysql_error());
}

$pgnumber = intval($_GET['pgnumber']);
if ($_GET['pgnumber']) {
	$page_limit1 = $pgnumber*10-10;
	$query = "SELECT * FROM tbl_news ORDER BY id DESC LIMIT $page_limit1,10";
} else {
	$query = "SELECT * FROM tbl_news ORDER BY id DESC LIMIT 0,10";
}

$query_result = mysql_query ($query)
  or die ("Невозможно сделать запрос");
$rows = mysql_num_rows ($query_result);

?>
  <a href="index.php?admin_page=admin_add_new" class="admin_add_button"> Добавить новость </a>
  <div id="pg_news_content">
	<?php
	
    for ($row=$rows;$row>=1;$row--) {
		
    $novost = zapros_novosti($query_result);
	if ($novost['tbl_thumbnail_link']) {
	$thumbnail_name = $novost['tbl_thumbnail_link'];
	$thumbnail_link = ('../images/temp_news_images/'.$thumbnail_name);
	}
    echo ('
            <table cellpadding="0" cellspasing="0" id="pg_news_table">
              <tr>
                <td class="pg_news_td top">
                Опубликовано: '.$novost[tbl_date].' | Автор: '.$novost[tbl_author].'
                </td>
				<td rowspan="2" class="admin_news_edit">
				<a href="index.php?admin_page=admin_edit_new&id='.$novost[tbl_key].'" class="admin_news_edit edit"></a> <a href="index.php?page=newslist&pgnumber='.$_GET[pgnumber].'&deleteid='.$novost[tbl_key].'" class="admin_news_edit delete">DEL</a>
				</td>
              </tr>
              <tr>
                <td class="pg_news_td middle">
                  <a href="index.php?admin_page=admin_edit_new&id='.$novost[tbl_key].'" class="pg_news_name">'.$novost[tbl_name].'</a>');
        if ($novost[tbl_image_link]) {
            echo ('<img align="left" border="0" class="pg_news_image" src="'.$thumbnail_link.'" />');
        }
    echo ('<p class="pg_news_text">'.$novost[tbl_text].'</p>
                </td>
              </tr>
            </table>
        ');}
   ?>
  </div>
      <table id="pg_news_pagecount_table" cellpadding="0" cellspacing="0">
        <tr>
          <td class="pg_news_pagecount_td">
          <?php
		      $news_pages=intval(($allrows[0]-1)/10);
		      $news_pages1=$news_pages+1;
		      if ($_GET['pgnumber']) {
			  echo ('<a href="index.php?page=newslist&pgnumber='.($pgnumber-1).'" class="pg_news_nextprev left">&lt Предыдущая</a>');
		  }
			  ?>
          </td>
          <td class="pg_news_pagecount_td links">
            <a class="pg_news_pagecountlink" href="index.php?page=newslist">1-10</a><?php
			for ($i=1; $i<=$news_pages; $i++) {
				$id=$i+1;
				echo ('<a class="pg_news_pagecountlink" href="index.php?page=newslist&pgnumber='.($id).'">'.((10+$i*10)-9).'-'.(10+10*$i).'</a>');
			}
			?>
          </td>
          <td class="pg_news_pagecount_td">
          <?php
		  if (isset($_GET['pgnumber'])) {
		      if ($_GET['pgnumber'] < $news_pages1) {
			  echo ('<a href="index.php?page=newslist&pgnumber='.($pgnumber+1).'" class="pg_news_nextprev">Следующая &gt</a>');
			  }
		  } else {
			  echo ('<a href="index.php?page=newslist&pgnumber=2" class="pg_news_nextprev">Следующая &gt</a>');
		  }
		  ?>
          </td>
        </tr>
    </table>
    