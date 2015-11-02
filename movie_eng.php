<?php 


mysql_connect ( "localhost", "xye_movies", "movies" );
mysql_select_db ( "xye_movies" );



if($_GET['action'] == "myList")
{
	header ("content-type: text/xml");
	echo "<root>";
		$zap = mysql_query ( "DELETE * FROM movies_bought WHERE login = '".$_GET['user']."' and NOW() > date" );	
	$zap = mysql_query ( "SELECT movies.id,title,file,screenshot,description,price,year,duration,note,date FROM  movies_bought LEFT JOIN movies ON  movies_bought.movie = movies.id LEFT JOIN users ON user = users.id WHERE login = '".$_GET['user']."' and NOW() <= date ORDER BY date LIMIT 0,4 " );	
		if (mysql_num_rows ( $zap ) > 0) {
			
			while ( $wiersz = mysql_fetch_row ( $zap ) ) {
				echo"<item>";
					echo"<id>".$wiersz[0]."</id>";
					echo"<title>".$wiersz[1]."</title>";
					echo"<file>".$wiersz[2]."</file>";
					echo"<screen>".$wiersz[3]."</screen>";
					echo"<desc>".$wiersz[4]."</desc>";	
					echo"<price>".$wiersz[5]."</price>";	
					echo"<year>".$wiersz[6]."</year>";	
					echo"<duration>".$wiersz[7]."</duration>";	
					echo"<note>".$wiersz[8]."</note>";
					echo"<date>".strtotime($wiersz[9])."</date>";	
					echo"<timer>".(strtotime($wiersz[9])-time())."</timer>";	
				echo"</item>";
			
			}
		}
	echo "</root>";

}
if($_GET['action'] == "newestList")
{
	header ("content-type: text/xml");
	echo "<root>";
	$zap = mysql_query ( "SELECT movies.id,title,file,screenshot,description,price,year,duration,note FROM movies WHERE movies.id NOT IN (SELECT movie FROM movies_bought LEFT JOIN users ON movies_bought.user = users.id WHERE login = '".$_GET['user']."') ORDER BY time_add LIMIT 0,9" );	
		if (mysql_num_rows ( $zap ) > 0) {
		while ( $wiersz = mysql_fetch_row ( $zap ) ) {
				echo"<item>";
				echo"<id>".$wiersz[0]."</id>";
					echo"<title>".$wiersz[1]."</title>";
					echo"<file>".$wiersz[2]."</file>";
					echo"<screen>".$wiersz[3]."</screen>";
					echo"<desc>".$wiersz[4]."</desc>";		
					echo"<price>".$wiersz[5]."</price>";	
					echo"<year>".$wiersz[6]."</year>";	
					echo"<duration>".$wiersz[7]."</duration>";	
					echo"<note>".$wiersz[8]."</note>";
				echo"</item>";
			
			}
		}
	echo "</root>";

}
if($_GET['action'] == "catalog")
{
	header ("content-type: text/xml");
	echo "<root>";
	$zap = mysql_query ( "SELECT movies.id,title,file,screenshot,movies.description,price,year,duration,note,movies_category.name,time_add FROM movies LEFT JOIN movies_category ON movies_category.id = category WHERE movies.id NOT IN (SELECT movie FROM movies_bought LEFT JOIN users ON movies_bought.user = users.id WHERE login = '".$_GET['user']."') ORDER BY time_add LIMIT 0,9" );	
		if (mysql_num_rows ( $zap ) > 0) {
		while ( $wiersz = mysql_fetch_row ( $zap ) ) {
				echo"<item>";
				echo"<id>".$wiersz[0]."</id>";
					echo"<title>".$wiersz[1]."</title>";
					echo"<file>".$wiersz[2]."</file>";
					echo"<screen>".$wiersz[3]."</screen>";
					echo"<desc>".$wiersz[4]."</desc>";		
					echo"<price>".$wiersz[5]."</price>";	
					echo"<year>".$wiersz[6]."</year>";	
					echo"<duration>".$wiersz[7]."</duration>";	
					echo"<note>".$wiersz[8]."</note>";
					echo"<category>".$wiersz[9]."</category>";
					echo"<add>".$wiersz[10]."</add>";
				echo"</item>";
			
			}
		}
	echo "</root>";

}
if($_GET['action'] == "buyMovie")
{	
	$zap = mysql_query ( "SELECT account,id FROM users WHERE login = '".$_GET['user']."'" );	
	$account = 0;
	$id = 0;
	while ( $wiersz = mysql_fetch_row ( $zap ) ) {
		$account =	$wiersz[0];
		$id = 	$wiersz[1];
	}
	
	$zap = mysql_query ( "SELECT movies.price FROM movies WHERE id = ".$_GET['movie']."" );	
	$price = 0;
	while ( $wiersz = mysql_fetch_row ( $zap ) ) {
		$price = $wiersz[0];
	}
	if($price <= $account)
	{
		$zap = mysql_query ( "UPDATE users SET account = ".($account-$price)." WHERE login = '".$_GET['user']."'" );	
		$zap = mysql_query ( "INSERT INTO movies_bought (`user`,`movie`,`date`) VALUES (".$id.",".$_GET['movie'].",NOW())" );			
		echo $price;
	
	}
	else
	{
		echo 0;
	
	}
	


}
if($_GET['action'] == "infoMovie")
{
	header ("content-type: text/xml");
	echo "<root>";
	$zap = mysql_query ( "SELECT movies.id,title,file,screenshot,description,price,year,duration,note,time_add,movies_bought.date,screenshot_big FROM movies LEFT JOIN movies_bought ON movies_bought.user = (SELECT id FROM users WHERE login= '".$_GET['user']."') and movies_bought.movie = movies.id  WHERE  movies.id = ".$_GET["movie"]."" );	
		if (mysql_num_rows ( $zap ) > 0) {
		
		while ( $wiersz = mysql_fetch_row ( $zap ) ) {

					echo"<id>".$wiersz[0]."</id>";
					echo"<title>".$wiersz[1]."</title>";
					echo"<file>".$wiersz[2]."</file>";
					echo"<screen>".$wiersz[3]."</screen>";
					echo"<screen_big>".$wiersz[11]."</screen_big>";
					echo"<desc>".$wiersz[4]."</desc>";		
					echo"<price>".$wiersz[5]."</price>";	
					echo"<year>".$wiersz[6]."</year>";	
					echo"<duration>".$wiersz[7]."</duration>";	
					echo"<note>".$wiersz[8]."</note>";
					echo"<add>".date("Y-m-d",$wiersz[9])."</add>";
					
					if($wiersz[10] !== NULL)
					{
						echo"<owned>1</owned>";
						echo"<bought>".$wiersz[10]."</bought>";
					}
					else
					{
						echo"<owned>0</owned>";
						echo"<bought>-</bought>";
					}
					
			
			}
		
		
		
		
		
		
		
		}

	echo "</root>";

	
}



if($_GET['action'] == "login")
{

	$zap = mysql_query ( "SELECT account FROM users WHERE login='".$_GET['login']."' and password='".$_GET['password']."' " );
		
		if (mysql_num_rows ( $zap ) > 0) {	
			$account = 0;
			while ( $wiersz = mysql_fetch_row ( $zap ) ) {
				$account = $wiersz[0];
			}
			echo $account;
		}
		else
		{
			echo -2;
		}

}



?>