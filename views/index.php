<html>
  <head>
    <title>Fuuze PHP framework demo guestbook</title>
  </head>
  <style type="text/css">
    body {
	width: 800px;
	margin: 0 auto;
	padding: 10px;
	/* border: 2px solid silver; */
	/* -moz-border-radius: 10px; */
    }
    label {
	font-weight: bold;
    }
    textarea {
	width: 600px;
	height: 250px;
    }
  </style>
  <body>
    <?php
      foreach ($this->posts as $post){
	echo htmlspecialchars($post['content']).'<br />--<br />';
      }
    ?>
    <form action="." method="post">
      <label>Text:</label><br />
      <?php
	if (isset($this->_errors['content'])):
      ?>
      <div class="errors"><?php echo $this->_errors['content']; ?></div>
      <?php
	endif;
      ?>
      <textarea name="Post[content]"><?php echo isset($this->data['content']) ? $this->data['content'] : ''; ?></textarea><br />
      <button>Post</button>
    </form>
  </body>
</html>