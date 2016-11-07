<?php

// NOTICEエラー無視
error_reporting(E_ALL & ~E_NOTICE);

require_once('funcs.php');

// バリデーション
if(!empty($_POST)){
  if($_POST['name'] == ''){
    $error['name'] = 'blank';
  }
  if($_POST['pic_name'] == ''){
    $error['pic_name'] = 'blank';
  }
  // if(empty($_POST['pic']){
  //   $error['pic'] == 'blank';
  // }
  $fileName = $_FILES['pic']['name'];
  if(!empty($fileName)){
    $ext = substr($fileName, -3);
    if($ext != 'jpg' && $ext != 'png'){
      $error['pic'] = 'type';
    }
  }

  // 投稿の記録
  if(empty($error)){
    move_uploaded_file($_FILES['pic']['tmp_name'], './pic/' . $fileName);

    $dbh = connectDb();
    $sql = "INSERT INTO posts (name,pic_name,picture,created) values
      (:name,:pic_name,:picture,now()) ";
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':pic_name', $pic_name);
    $stmt->bindParam(':picture',$picture);

    $name = $_POST['name'];
    $pic_name = $_POST['pic_name'];
    $picture = './pic/' . $fileName;

    $stmt->execute();

    header('Location: index.php');
    exit();
  }
}

// 投稿の取得
  $dbh = connectDb();
  $sql = "SELECT * FROM posts ORDER BY created DESC";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();

  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


// htmlspecialcharsメソッド
function h($value){
  return htmlspecialchars($value,ENT_QUOTES,'utf-8');
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
  <script src="js/dropzone.js"></script>
  <title>（画像のアップロード）</title>
</head>
<body>
  <h2>画像を投稿できる掲示板</h2>
  <form action="" method="post" enctype="multipart/form-data">
  <dl>
    <dt>
      お名前<span class="required" style="background:blue; color: white;">必須:</span>
    </dt>
    <dd style="padding-bottom: 20px;">
      <input type="text" name="name" size="35" maxlength="100">
      <?php if($error['name'] == 'blank'): ?>
        <p style="color:red; margin:0;">* お名前を入力してください。</p>
      <?php endif; ?>
    </dd>
    <dt>
      画像タイトル<span class="required" style="background:blue; color: white;">必須:</span>
    </dt>
    <dd style="padding-bottom: 20px;">
      <input type="text" name="pic_name" size="35" maxlength="100">
      <?php if($error['pic_name'] == 'blank'): ?>
        <p style="color:red; margin:0;">* 画像タイトルを入力してください。</p>
      <?php endif; ?>
    </dd>
    <dt>
      画像ファイル:
    </dt>
    <dd style="padding-bottom: 20px;">
      <input type="file" name="pic" class="dropzone">
      <?php if($error['pic'] == 'type'): ?>
        <p style="color:red; margin:0;">* 画像は「.jpg」または「.png」を指定してください</p>
      <?php endif; ?>
    </dd>
  </dl>
  <input type="submit" value="投稿する">
  </form>

<!-- 投稿表示 -->
  <?php foreach($rows as $row) : ?>
    <div class="msg" style="clear:both; border-top:1px solid #ccc; padding-top:10px;">
      <img src="<?php echo h($row['picture']); ?>" width="100" height="100" alt="<?php echo h($row['pic_name']); ?>" style="float:left; margin-right:5px;">
        <p style="margin:0;">
          画像タイトル：<?php echo h($row['pic_name']); ?>
        </p>
        <p>
          投稿者：<?php echo h($row['name']); ?>
        </p>
        <p style="font-size:80%;">
          投稿日：<?php echo h($row['created']); ?>
        </p>

    </div>
  <?php endforeach; ?>

</body>
</html>















