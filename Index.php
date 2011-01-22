<?php


class Index extends Fuuze {


  public function home() {

    if (isset($_POST['Post'])){

      $this->data = $_POST['Post'];

      if (trim($this->data['content'])){
	$dbd = $this->dbh->prepare("INSERT INTO post (content) VALUES (:content)");
	$dbd->bindParam(':content', $this->data['content']);
	$dbd->execute();
	unset($this->data['content']);
      } else {
	$this->_errors['content'] = 'Input too short.';
      }
    }

    //Fetch db
    $sql = 'SELECT content FROM post ORDER BY id';
    $this->posts = $this->dbh->query($sql);

    $this->render('index.php');
  }

}