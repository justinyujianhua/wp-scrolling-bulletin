曾经为大家介绍过如何在Wordpress中创建自定义文章类型 ，具体如何创建可以看看 - WORDPRESS自定义文章类型∶添加公告功能 。但最近在做一个项目的时候，发现自定义文章类型的分页列表（previous_posts_link、next_posts_link）却无法调用出来。回去认真检查了本站的公告页面也是存在这个问题，获取不到分页列表（本站是自动无限翻页功能失效，通过下面的方法已经完美解决。）

wordpress-custom-post-type-pagination

在网上搜索了一番，发现这类问题存在的挺多的，但大部分问题出现在国外，国内估计比较少发现这类问题。通过查找资料，终于找到了解决方法，在这里分享给大家，希望可以给遇到此类问题的同学一个解决方案。

通常我们在创建完自定义文章类型后，就在后台新建一个页面，然后通过页面模版调用。分页的代码是出现在页面模版中，我们一般的调用代码是：

<?php
      $loop = new WP_Query( array( 'post_type' => 'projects' ) );
      while ( $loop->have_posts() ) : $loop->the_post();
?>

  <!-- 其他代码 --> 

<?php endwhile; ?>
<nav>
  <?php previous_post_link('&laquo; '); ?>
  <?php next_post_link(' &raquo;'); ?>
</nav>
通过这样子的调用，文章是可以显示完整。但下面的分页却不显示，语法也没有用错。在这里其实需要了解到wp_query的用法，在这里就不多介绍了，以后有机会再谈。那么我们来看看解决方法吧，将上面代码改成如下：

<?php 
  $temp = $wp_query; 
  $wp_query = null; 
  $wp_query = new WP_Query(); 
  $show_posts = 4;  //How many post you want on per page
  $permalink = 'Post name'; // Default, Post name
  $post_type = 'projects';

  //Know the current URI
  $req_uri =  $_SERVER['REQUEST_URI'];  

  //Permalink set to default
  if($permalink == 'Default') {
  $req_uri = explode('paged=', $req_uri);

  if($_GET['paged']) {
  $uri = $req_uri[0] . 'paged='; 
  } else {
  $uri = $req_uri[0] . '&paged=';
  }
  //Permalink is set to Post name
  } elseif ($permalink == 'Post name') {
  if (strpos($req_uri,'page/') !== false) {
  $req_uri = explode('page/',$req_uri);
  $req_uri = $req_uri[0] ;
  }
  $uri = $req_uri . 'page/';

  }

  //Query
  $wp_query->query('showposts='.$show_posts.'&post_type='. $post_type .'&paged='.$paged); 
  //count posts in the custom post type
 $count_posts = wp_count_posts('projects');

  while ($wp_query->have_posts()) : $wp_query->the_post(); 
  ?>

  <!-- 其他代码-->

  <?php endwhile;?>
  <nav>
  <?php previous_posts_link('« ') ?>
  <?php
  $count_post = $count_posts->publish / $show_posts;

  if( $count_posts->publish % $show_posts == 1 ) {
  $count_post++;
  $count_post = intval($count_post);
  };

  for($i = 1; $i <= $count_post ; $i++) { ?>
  <a <?php if($req_uri[1] == $i) { echo 'class=active_page'; } ?> href="<?php echo $uri . $i; ?>"><?php echo $i; ?></a>
  <?php }
  ?>
  <?php next_posts_link(' »') ?>
  </nav>

  <?php 
  $wp_query = null; 
  $wp_query = $temp;  // Reset
  ?>
上面的代码可以根据你的需求，自行更改固定链接$permalink，同样$post_type更改成你所创建的自定义文章的名称。