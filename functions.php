<!-- 发布公告功能（在wordpress中增加该项目） -->
function post_type_bulletin() {
register_post_type(
	'bulletin', 
	array( 'public' => true,
		'publicly_queryable' => true,
		'hierarchical' => false,
		'labels'=>array(
			'name' => _x('公告', 'post type general name'),
			'singular_name' => _x('公告', 'post type singular name'),
			'add_new' => _x('添加新公告', '公告'),
			'add_new_item' => __('添加新公告'),
			'edit_item' => __('编辑公告'),
			'new_item' => __('新的公告'),
			'view_item' => __('预览公告'),
			'search_items' => __('搜索公告'),
			'not_found' =>  __('您还没有发布公告'),
			'not_found_in_trash' => __('回收站中没有公告'), 
			'parent_item_colon' => ''
			),
		 'show_ui' => true,
		 'menu_position'=>5,
			'supports' => array(
			'title',
			'author', 
			'excerpt',
			'thumbnail',
			'trackbacks',
			'editor', 
			'comments',
			'custom-fields',
			'revisions'	) ,
		'show_in_nav_menus'	=> true ,
		'taxonomies' => array(	
		    'menutype',
			'post_tag')
			) 
	); 
} 
add_action('init', 'post_type_bulletin');
<!-- 创建公告分类 -->
function create_genre_taxonomy() {
  $labels = array(
		 'name' => _x( '公告分类', 'taxonomy general name' ),
		 'singular_name' => _x( 'genre', 'taxonomy singular name' ),
		 'search_items' =>  __( '搜索分类' ),
		 'all_items' => __( '全部分类' ),
		 'parent_item' => __( '父级分类目录' ),
		 'parent_item_colon' => __( '父级分类目录:' ),
		 'edit_item' => __( '编辑公告分类' ), 
		 'update_item' => __( '更新' ),
		 'add_new_item' => __( '添加新公告分类' ),
		 'new_item_name' => __( 'New Genre Name' ),
  ); 
  register_taxonomy('genre',array('bulletin'), array(
         'hierarchical' => true,
         'labels' => $labels,
         'show_ui' => true,
         'query_var' => true,
         'rewrite' => array( 'slug' => 'genre' ),
  ));
}
add_action( 'init', 'create_genre_taxonomy', 0 );



<!-- 可以将这段代码直接加到functions.php文件中，或者为了方便管理代码也可以把上面的代码复制下来然后保存文件为bulletin-post-types.php，
然后再引入functions.php文件中：
include("bulletin-post-types.php");
请注意引入的bulletin-post-types.php文件的存放路径，这里的路径是在functions.php文件所在目录的当前目录下。
 -->



<!-- 调用自定义公告放到页面部分 -->

<div id="announcement_box"  class="ption_a">
  <div id="announcement">
    <ul style="margin-top: 0px;">
      <?php
                $loop = new WP_Query( array( 'post_type' => 'bulletin', 'posts_per_page' => 5 ) );
                // 5为调用显示条数
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>
      <li><span class="mr10">
        <?php the_time('Y-n-j H:i') ?>
        </span><a href="http://www.jiawin.com/bulletin-archive/#bulletin-<?php the_ID(); ?>" title="<?php the_title(); ?>"><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 70,"…"); ?></a></li>
        <!-- 70为显示字符数 -->
      <?php endwhile; wp_reset_query(); ?>
    </ul>
  </div>
  <div class="announcement_remove"><a title="关闭" href="javascript:void(0)" onClick="$('#announcement_box').slideUp('slow');"><span id="announcement_close">×</span></a></div>
</div>


<!-- css部分 -->
#announcement_box {background-color:rgba(240, 239, 215, 0.5); background-color:#E3DEC0 \9; margin:0 0 0 40px; border:1px dashed #C1C0AB; border-radius:2px; padding-left:10px; top:42px; width:674px;}
#announcement {background:url(images/notice_icon.png) left center no-repeat scroll; height:25px; line-height:25px; overflow: hidden; padding: 5px 10px 5px 20px; float:left;}
#announcement a {color:#000;}
#announcement a:hover {color:#94382B;}
.announcement_remove {padding:5px 10px; float:right; font-size:14px;}
.announcement_remove a {height:18px; width:18px; display:block; line-height:16px; margin:4px 0 3px 0; margin:10px 0 3px 0 \9; text-align:center;}
.announcement_remove a:hover {background-color:#cdc8a0; box-shadow:1px 1px 1px #66614c inset; -webkit-box-shadow:1px 1px 1px #666 inset; -moz-box-shadow:1px 1px 1px #666 inset; border-radius:3px;}
#announcement_close {color:#666;}
#announcement span {color:#666;}


<!-- 滚动效果js -->
function AutoScroll(obj){ 
	$(obj).find("ul:first").animate({ 
		marginTop:"-25px" 
	},500,function(){ 
		$(this).css({marginTop:"0px"}).find("li:first").appendTo(this); 
		}); 
} 
$(document).ready(function(){ 
	setInterval('AutoScroll("#announcement")',4000) 
});
