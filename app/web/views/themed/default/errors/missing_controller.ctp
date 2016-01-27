<div class="am-cf am-padding">
  <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">404</strong> / <small>Not Found</small></div>
</div>
<!--错误提示页-->
<div class="am-g">
  <div class="am-u-sm-12">
	<h2 class="am-text-center am-text-xxxl am-margin-top-lg">404. Not Found</h2>
	<p class="am-text-center"><?php echo $ld['no_page'] ?></p>
	<pre class="page-404 am-text-center">
      .----.
   _.'__    `.
.--($)($$)---/#\
.' @          /###\
:         ,   #####
`-..__.-' _.-\###/
    `;_:    `"'
  .'"""""`.
 /,  ya ,\\
//  404!  \\
`-._______.-'
___`. | .'___
(______|______)
    </pre>
	<div class="am-text-center"><span><?php echo $html->link($ld['back_page'],"javascript:history.go(-1);",'',false,false);?> | <?php echo $html->link($ld['home'], '/')?> | <?php echo $html->link($ld['back_to_login_page'], '/users/login')?></span></div>
  </div>
</div>