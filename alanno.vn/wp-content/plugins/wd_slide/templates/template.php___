<?php
	$_wd_portfolio = new WD_Portfolio;
	$columns = 4;
?>
						<div id="portfolio-container">
							<div id="portfolio-container-holder">	
								<div class="portfolio-galleries" id="portfolio-galleries">
									<?php $terms = get_terms('wd-portfolio-category',array('hide_empty'=>true)); ?>
									<input class="limited" type="hidden" value="<?php echo get_option( 'posts_per_page' ) ;?>" />
									<?php if( $columns > 1 ) : ?>
									<div>	
										<ul class="portfolio-filter">
											<li id="all" class="active"><a href="javascript:void(0)" id="all_a" class="filter-portfoio active"><?php _e('ALL','wpdance');?></a></li>
										<?php foreach( $terms as $term ) : ?>
											<li id="<?php echo esc_html($term->slug) ; ?>"><a href="javascript:void(0)" id="<?php echo esc_html($term->slug) ; ?>_a" class="filter-portfoio"><?php echo esc_html(get_term($term,'wd-portfolio-category')->name); ?></a></li>
										<?php endforeach;?>
										</ul>
									</div>
									<?php endif; ?>
								
									<?php $terms=get_terms('wd-portfolio-category',array('hide_empty'=>true)); ?>
									<input class="limited" type="hidden" value="<?php echo get_option('posts_per_page' ) ;?>" />
									<div id="portfolio-galleries-holder">
									<?php	
										$title_icon = "";
										query_posts('post_type=portfolio&posts_per_page='.get_option('posts_per_page').'&paged='.get_query_var('page'));$count=0;
										if(have_posts()) : while(have_posts()) : the_post(); global $post;global $wp_query;
											$post_title = esc_html(get_the_title($post->ID));
											$post_url =  esc_url(get_permalink($post->ID));
											$url_video = esc_url(get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true));
											$term_list = implode( ' ', wp_get_post_terms($post->ID, 'wd-portfolio-category', array("fields" => "slugs")) );
											if( strlen( trim($url_video) ) > 0 ){
												$thumb=get_post_thumbnail_id($post->ID);
												if(strstr($url_video,'youtube.com') || strstr($url_video,'youtu.be')){
													//$thumburl = array(get_thumbnail_video_src($url_video , 400 ,228));
													$thumburl=wp_get_attachment_image_src($thumb,'blog_thumb');
													$item_class = "thumb-video youtube-fancy";
												}
												if(strstr($url_video,'vimeo.com')){
													//$thumburl = array(wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($url_video), 850, 480));	
													$thumburl=wp_get_attachment_image_src($thumb,'blog_thumb');
													$item_class = "thumb-video vimeo-fancy";
												}
												$light_box_url = $url_video;
												$title_icon = "Watch Video";
											}else{ 
												$thumb=get_post_thumbnail_id($post->ID);
												$thumburl=wp_get_attachment_image_src($thumb,'blog_thumb');
												$item_class = "thumb-image";
												$light_box_url = $thumburl[0];
												$title_icon = "Show Image";
											}
											$portfolio_slider = get_post_meta($post->ID,THEME_SLUG.'_portfolio_slider',true);
											$portfolio_slider = unserialize($portfolio_slider);
											$slider_thumb = false;
											if( is_array($portfolio_slider) && count($portfolio_slider) > 0 ){
												$slider_thumb = true;
												$item_class = "thumb-slider";
											}
											$post_count = $wp_query->post_count;
											$div_pos = $count % 3;
										?>
										<?php $class_span = 'span'.(24 / $columns);?>
										<div class="item <?php echo $class_span;?> item-portfolio<?php //if($count % $columns == 0)  echo " first"; ?><?php  //if($count % $columns == ($columns - 1) || ($count + 1) == $wp_query->post_count ){ echo " last";} ?>" data-type="<?php echo $term_list;?>" data-id="<?php echo $post->ID;?>">
											<div>	
												<div class="thumb-holder <?php echo $item_class;?>">
													<div class="thumbnail">	
														<div class="thumb-image post-item ">
															<?php if( $slider_thumb ){?>	
																	<div class="portfolio-slider">
																		<ul class="slides">
																			<?php foreach( $portfolio_slider as $slide ){ ?>	
																			<?php $_thumb_uri = wp_get_attachment_image_src( $slide['thumb_id'], 'blog_thumb', false );
																				$_thumb_uri = $_thumb_uri[0];
																				$_sub_thumb_uri = wp_get_attachment_image_src( $slide['thumb_id'], 'blog_thumb', false );
																				$_sub_thumb_uri = $_sub_thumb_uri[0]; 
																			?>
																				<li data-thumb="<?php  echo esc_url($_sub_thumb_uri);//echo print_thumbnail($slide['image_url'],true,$post_title,124,68,'',false,true); ?>"><a href="<?php echo esc_url($slide['url']);?>"><img alt="<?php echo esc_html($slide['alt']);?>" class="opacity_0" src="<?php echo  esc_url($_thumb_uri);//echo print_thumbnail($slide['image_url'],true,$post_title,620,340,'',false,true); ?>"/></a></li>
																				
																			<?php } ?>
																		</ul>	
																	</div>	
																<?php }else{ ?>	
																	
																	<a class="image" href="<?php echo $post_url; ?>">
																	<?php if($thumburl[0]) { ?>
																		<img alt="<?php echo $post_title?>" title="<?php echo $post_title;?>" class="opacity_0" src="<?php echo  esc_url($thumburl[0]);?>"/>																
																		<?php } else { ?>
																		<img alt="<?php echo $post_title?>" title="<?php echo $post_title;?>" class="opacity_0" src="<?php echo get_template_directory_uri(); ?>/images/no-image-blog.gif"/>
																	</a>
																	
																<?php } } ?>	
																	
															<!-- <div style="opacity: 0;" class="fdw-background"><h4><a href="<?php echo $post_url; ?>" style="color:#fff;">VIEW MORE PROPERTIES</a></h4></div> -->
															<div id="thumb-image-hover" class="hover-default">
																<div class="background opacity_6"></div>
																<div class="icons">
																	<a class="zoom-gallery fancybox <?php echo $item_class;?>" title="<?php echo $title_icon; ?>" rel="fancybox" href="<?php echo esc_url($light_box_url);?>"></a>
																	<a class="link-gallery " title="View more" href="<?php echo $post_url;?>"></a>
																</div>
															</div>
															<div class="thumb-hover-start"></div>													
														</div>
													</div>	
													<div class="thumb-tag">
														<h2 class="post-title heading-title list-title portfolio-grid-title">
															<a  href="<?php echo $post_url; ?>">
															<?php echo $post_title; ?>
															</a>
														</h2>
														<!--<div class="desc"><?php echo substr(get_the_content(),0,90); ?></div>-->
													</div>   										
												</div>
											</div>
										</div>
										<?php		
											$count++;
										endwhile;
										else : echo "Sorry.There are no posts to display";
										endif;	
									?>	
									</div>
								</div>
							</div>
							<div class="clear"></div>
							<div class="end_content alpha omega">
							   <div class="count_project"><span class="number_project"><?php echo wp_count_posts('portfolio')->publish; ?></span> Project<?php if(wp_count_posts('portfolio')->publish > 1) { echo 's'; } ?></div>
							   <div class="page_navi"><?php ew_pagination(); wp_reset_query();?></div>
							</div>
						</div>
					<script type="text/javascript">
							function untrigger_event_hover(data){
								data.children('.thumb-image .post-item,.thumb-video .post-item').unbind('hover');
							}
							function trigger_event_hover(){
								var backgOverColor      = "#3f3f3f";
								var backgOutColor       = '#141211';
								var text1BaseColor      = '#fff';
								jQuery('.thumb-image .post-item,.thumb-video .post-item').hover(
									function(event){ 
										jQueryelement = jQuery(this);
										var icon_zoom = jQuery(".icons > .zoom-gallery", this);
										var icon_link = jQuery(".icons > .link-gallery", this);								
										var w = jQueryelement.width(), h = jQueryelement.height();
										x = ( ( w/2 ) - icon_zoom.width() - 4  );//
										y = ( ( h/2 ) - icon_zoom.width()/2 );						
											customHoverAnimation( "over", event, jQuery(this), jQuery("#thumb-image-hover", this) ); 
											var text = jQuery(".thumb-hover-start", this);
											TweenMax.to( text, 1, { css:{ color: backgOutColor },  ease:Quad.easeOut });
											TweenMax.to( jQuery(this), 1, { css:{ backgroundColor: backgOverColor },  ease:Quad.easeOut });
											TweenMax.to(icon_zoom, .5, { css:{
												boxShadow: "0px 0px 24px 6px white",
												borderRadius:"50%",
												rotation: 360,
												left:x,
												top: y
												},
												ease:Quad.easeOutBounce
											});
											TweenMax.to(icon_link, .5, { css:{
												boxShadow: "0px 0px 24px 6px white",
												borderRadius:"50%",
												rotation: 180,
												left:(x+48),
												top: y
												},
												ease:Quad.easeOutBounce
											});									
										},
										function(event){ 
											var icon_zoom = jQuery(".icons > .zoom-gallery", this);
											var icon_link = jQuery(".icons > .link-gallery", this);
											customHoverAnimation( "out", event, jQuery(this), jQuery("#thumb-image-hover", this) ); 
											var text = jQuery(".thumb-hover-start", this);
											TweenMax.to( text, 1, { css:{ color: text1BaseColor },  ease:Circ.easeOut });
											TweenMax.to( jQuery(this), 1, { css:{ backgroundColor: backgOutColor },  ease:Quad.easeOut });
											TweenMax.to(icon_zoom, 0.5, { css:{
												boxShadow: "0px 0px 24px 6px black",
												rotation: 0,
												left:"0",
												top: "0"										
												}
											});
											TweenMax.to(icon_link, 0.5, { css:{
												boxShadow: "0px 0px 24px 6px black",
												rotation: 0,
												left:"100%",
												top: "100%"										
												}
											});										
								});	
							
							}
							
							jQuery(function() {
								
								//flexslider slider
								if(jQuery('.portfolio-slider ul').length > 0 ){
									window.setTimeout( function(){
										var li_width = jQuery('#portfolio-galleries-holder').width() / <?php echo $columns ?> - 20;
										
										jQuery('.portfolio-slider').each(function(i,value){
											var control_prev =  jQuery('<div class="wd_portfolio_control_' + i +'"><a class="prev" id="wd_portfolio_prev_" href="#">&lt;</a><a class="next" id="wd_portfolio_next_" href="#" >&gt;</a> </div>');
											jQuery(value).append(control_prev);
											jQuery(value) .children('ul').carouFredSel({
												responsive: true
												,width	: li_width
												,scroll  : {
													items	: 1,
													auto	: true,
													pauseOnHover    : true
												}
												,swipe	: { onMouse: true, onTouch: true }
												,auto    : true
												,items   : { 
													width		: li_width
													,height		: 'auto'					
												}
												,prev    : '.wd_portfolio_control_' + i +' #wd_portfolio_prev_'
												,next 	: '.wd_portfolio_control_' + i +' #wd_portfolio_next_'
											});
										});	
									},0);	
								}
								trigger_event_hover();
								var applications = jQuery('#portfolio-galleries-holder');
								applications.find('div.item-portfolio').each(function(i,value){
									if(i % <?php echo $columns ?> == 0 ) { jQuery(this).addClass('first') ; };
									if(i % <?php echo $columns ?> == <?php echo $columns - 1; ?> || i == <?php echo $post_count - 1; ?> ) { jQuery(this).addClass('last');} ;
								});
								<?php if( $columns > 1 ) : ?>
								var filterType = jQuery('.portfolio-filter > li');
								var data = applications.clone();
								var flag = 0;
								// attempt to call Quicksand on every form change
								
								filterType.click(function(e) {
									if(!jQuery(this).hasClass('active')){
										var list_id = [];
										jQuery('.portfolio-filter > li.active').removeClass('active');
										jQuery(this).addClass('active');
										if (jQuery(this).attr('id') == 'all') {
											var filteredData = data.find('div.item-portfolio');
										} else {	
											var filteredData = data.find('div.item-portfolio[data-type~=' + jQuery(this).attr('id') + ']');
										}
	
										
										for( i = 0 ; i < filteredData.length ; i++ ){
											list_id.push(filteredData.eq(i).attr('data-id'));
											var li_width = jQuery('#portfolio-galleries-holder').width() / <?php echo $columns ?> - 20;
											if( filteredData.eq(i).find('.portfolio-slider').length > 0 ){
												var new_slider = jQuery('<ul class="slides"></ul>');
												filteredData.eq(i).find('ul.slides > li').not('.clone').appendTo(new_slider)/*.filter(':not(:first)').hide()*/;
												new_slider.css('height',li_width + 'px').height(li_width).css('width',li_width + 'px').css('overflow','hidden');
												var control_prev =  jQuery('<div class="wd_portfolio_control' + i +'"><a class="prev" id="wd_portfolio_prev_" href="#">&lt;</a><a class="next" id="wd_portfolio_next_" href="#" >&gt;</a> </div>');
												
												filteredData.eq(i).find('.portfolio-slider').html(new_slider);//.append(control_prev);
											}
										}

										if(flag != 0){
											console.log('flag_0');
											endModuleGallery(false);
										}
										
										window.setTimeout( function(){
											
											applications.quicksand(filteredData, {
													duration: 0
													,easing: 'easeInOutQuad'
												},function() {
													if(filteredData.length > 0){
														moduleGallery();
														
														jQuery('.not-found-wrapper').hide();
														if(jQuery('.portfolio-slider ul').length > 0 ){
															var li_width = jQuery('#portfolio-galleries-holder').width() / <?php echo $columns ?> - 20;
															jQuery('.portfolio-slider').each(function(i,value){
																var control_prev =  jQuery('<div class="awd_portfolio_control_' + i +'"><a class="prev" id="wd_portfolio_prev_" href="#">&lt;</a><a class="next" id="wd_portfolio_next_" href="#" >&gt;</a> </div>');
																var check = 0;
																if(jQuery(value).children('div.awd_portfolio_control_'+i).length > 0) {
																	check = 1;
																	//console.log(jQuery(value).children('div.awd_portfolio_control_'+i).html());
																} 
																if(check == 0)
																	jQuery(value).append(control_prev);	
																															
																
																
																jQuery(value).children('ul').carouFredSel({
																	responsive: true
																	,width	: li_width
																	,scroll  : {
																		items	: 1,
																		auto	: true,
																		pauseOnHover    : true
																	}
																	,swipe	: { onMouse: true, onTouch: true }
																	,auto :true
																	,debug	 : true
																	,items   : { 
																		width		: li_width
																		,height		: 'auto'					
																	}
																	
																	,prev    : '.awd_portfolio_control_' + i +' #wd_portfolio_prev_:first'
																	,next 	: '.awd_portfolio_control_' + i +' #wd_portfolio_next_:first'		
																});
															});
														}
														trigger_event_hover();
														jQuery('#portfolio-galleries-holder').height('auto');
													}else{
														jQuery('.not-found-wrapper').show();
													}
													
													
											});
											applications.find('div.item-portfolio').removeClass('first').removeClass('last');
											var count = 0;
											for( i = 0 ; i < list_id.length ; i++ ){
												var temp = jQuery('#portfolio-galleries-holder div.item-portfolio[data-id='+list_id[i]+']');
												if(i % <?php echo $columns ?> == 0 ) { 
													jQuery(temp).addClass('first') ; 
												}
												if(i % <?php echo $columns ?> == <?php echo $columns - 1; ?>  ) { 
													jQuery(temp).addClass('last');
												}
											}
											
											
										}, flag );
										
										flag = 1500;	
									}
								});
								<?php endif; ?>
							});
						</script>