<?php
require_once('blog.php');
$blog = new Blog();
$blogDate = $blog->getAll();//取得したデータ
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ブログ一覧</title>
</head>
<body>
    <h2>ブログ一覧</h2>
    <p><a href="/form.html">新規作成</a></p>
    <table>
        <tr>
            <th>タイトル</th>
            <th>カテゴリ</th>
            <th>投稿日時</th>
        </tr>
        <?php foreach($blogDate as $column): ?>
        <tr>
            <td><?php echo h($column['title']) ?></td>
            <td><?php echo h($blog->setCategoryName($column['category'])) ?></td>
            <td><?php echo h($column['post_at']) ?></td>
            <td><a href="/detail.php?id=<?php echo $column['id'] ?>">詳細</a></td>
            <td><a href="/update_form.php?id=<?php echo $column['id'] ?>">編集</a></td>
            <td><a href="/blog_delete.php?id=<?php echo $column['id'] ?>">削除</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>