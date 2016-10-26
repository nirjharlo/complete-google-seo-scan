<?php
 echo '<div class="updated"><p>' . __( 'Google Search Engine Optimization Scan for your webpage is complete. Here are the results.', 'cgss' ) . '</p></div>';

 $cgss_theme = wp_get_theme();
 $cgss_theme_name = wp_get_theme()->Name;

 $cgss_body = file_get_contents( $cgss_url, FILE_USE_INCLUDE_PATH );

 if ( $cgss_robot_command == 'cgss-robots-command' ) {
  $cgss_robot_file_headers = get_headers( home_url() . '/robots.txt/', 1 );
  if ( ! strpos( $cgss_robot_file_headers[0], '404 Not Found' ) ) {
  $cgss_robot_file = file_get_contents( home_url() . '/robots.txt/', FILE_USE_INCLUDE_PATH );
  }
 }

 if ( $cgss_sitemap_input != null ) {
  $cgss_sitemap_file_headers = get_headers( $cgss_sitemap_input, 1 );
  if ( ! strpos( $cgss_sitemap_file_headers[0], '404 Not Found' ) ) {
  $cgss_sitemap_file = file_get_contents( $cgss_sitemap_input, FILE_USE_INCLUDE_PATH );
  }
 }

 //load time counter stop
 $cgss_load_time = microtime();
 $cgss_load_time = explode(' ', $cgss_load_time);
 $cgss_load_time = $cgss_load_time[1] + $cgss_load_time[0];
 $cgss_load_finish = $cgss_load_time;
 $total_load_time = round(($cgss_load_finish - $cgss_load_start), 2);
 $cgss_load_time_consumed = $total_load_time;

 //scan time counter start
 $cgss_scan_time = microtime();
 $cgss_scan_time = explode( ' ', $cgss_scan_time );
 $cgss_scan_time = $cgss_scan_time[1] + $cgss_scan_time[0];
 $cgss_scan_start = $cgss_scan_time;

 //declare dom
 $cgss_dom = new DOMDocument;
 libxml_use_internal_errors( true );
 $cgss_dom->loadHTML( $cgss_body );

 //get link details from dom document
 $cgss_links = $cgss_dom->getElementsByTagName( 'a' );
 $cgss_link_href = array();
 $cgss_outgoing_link = array();
 $cgss_link_rel = array();
 $cgss_nofollow_link = array();
 $cgss_link_title = array();
 foreach ( $cgss_links as $cgss_link ) {
  $cgss_link_href_value = $cgss_link->getAttribute( 'href' );
  $cgss_link_rel_value = $cgss_link->getAttribute( 'rel' );
  $cgss_link_title_value = $cgss_link->getAttribute( 'title' );
  if ( $cgss_link_href_value != null ) {
   $cgss_link_href[] = $cgss_link_href_value;
   if ( substr( $cgss_link_href_value, 0, strlen( home_url() ) ) != home_url() ) {
    $cgss_outgoing_link[] = $cgss_link_href_value;
   }
   if ( $cgss_link_rel_value != null ) {
    $cgss_link_rel[] = $cgss_link_rel_value;
    if ( strpos( $cgss_link_rel_value, 'nofollow' ) ) {
     $cgss_nofollow_link[] = $cgss_link_rel_value;
    }
   }
   if ( $cgss_link_title_value != null ) {
    $cgss_link_title[] = $cgss_link_title_value;
   }
  }
 }
 $cgss_link_count = count( $cgss_link_href );
 $cgss_outgoing_link_count = count( $cgss_outgoing_link );
 $cgss_link_rel_count = count( $cgss_link_rel );
 $cgss_nofollow_link_count = count( $cgss_nofollow_link );
 $cgss_link_title_count = count( $cgss_link_title );

 //get image details from dom document
 $cgss_images = $cgss_dom->getElementsByTagName( 'img' );
 $cgss_image_src = array();
 $cgss_image_alt = array();
 $cgss_image_height = array();
 $cgss_image_width = array();
 foreach ( $cgss_images as $cgss_image ) {
  $cgss_image_src_value = $cgss_image->getAttribute( 'src' );
  $cgss_image_alt_value = $cgss_image->getAttribute( 'alt' );
  $cgss_image_height_value = $cgss_image->getAttribute( 'height' );
  $cgss_image_width_value = $cgss_image->getAttribute( 'width' );
  if ( $cgss_image_src_value != null ) {
   $cgss_image_src[] = $cgss_image_src_value;
   if ( $cgss_image_alt_value != null ) {
    $cgss_image_alt[] = $cgss_image_alt_value;
   }
   if ( $cgss_image_height_value != null ) {
    $cgss_image_height[] = $cgss_image_height_value;
   }
   if ( $cgss_image_width_value != null ) {
    $cgss_image_width[] = $cgss_image_width_value;
   }
  }
 }
 $cgss_image_count = count( $cgss_image_src );
 $cgss_image_alt_count = count( $cgss_image_alt );
 $cgss_image_height_count = count( $cgss_image_height );
 $cgss_image_width_count = count( $cgss_image_width );

 //get script details from dom document
 $cgss_js_all = $cgss_dom->getElementsByTagName( 'script' );
 $cgss_js_src = array();
 foreach ( $cgss_js_all as $cgss_js ) {
  $cgss_js_src_value = $cgss_js->getAttribute( 'src' );
  if ( $cgss_js_src_value != null ) {
   $cgss_js_src[] = $cgss_js_src_value;
  }
 }
 $cgss_js_count = count( $cgss_js_src );

 //get css details from dom document
 $cgss_css_all = $cgss_dom->getElementsByTagName( 'link' );
 $cgss_css_href = array();
 foreach ( $cgss_css_all as $cgss_css ) {
  if ( $cgss_css->getAttribute( 'rel' ) == 'stylesheet' ) {
   $cgss_css_href_value = $cgss_css->getAttribute( 'href' );
   if ( $cgss_css_href_value != null ) {
    $cgss_css_href[] = $cgss_css_href_value;
   }
  }
 }
 $cgss_css_count = count( $cgss_css_href );

 //get heading tags details from dom document
 $text_head_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
 $text_head_tags_present = array();
 $text_name_head_tags_present = array();
 foreach ( $text_head_tags as $text_head_tag ) {
  $text_head_tags_node = $cgss_dom->getElementsByTagName( $text_head_tag );
  if ( $text_head_tags_node->length != 0 ) {
   $text_name_head_tags_present[] = $text_head_tag;
  }
 }
 $text_head_tag_count = count( $text_name_head_tags_present );
 foreach ( $text_name_head_tags_present as $text_name_head_tag_present ) {
  if ( $text_name_head_tag_present == 'h1' ) {
   $text_hone_head_tag = 'h1,&nbsp;';
  }
  if ( $text_name_head_tag_present == 'h2' ) {
   $text_htwo_head_tag = 'h2,&nbsp;';
  }
  if ( $text_name_head_tag_present == 'h3' ) {
   $text_hthree_head_tag = 'h3,&nbsp;';
  }
  if ( $text_name_head_tag_present == 'h4' ) {
   $text_hone_head_tag = 'h4,&nbsp;';
  }
  if ( $text_name_head_tag_present == 'h5' ) {
   $text_hfive_head_tag = 'h5,&nbsp;';
  }
  if ( $text_name_head_tag_present == 'h6' ) {
   $text_hsix_head_tag = 'h6,&nbsp;';
  }
 }
 $cgss_condition_head_tags = array( $text_hone_head_tag, $text_htwo_head_tag, $text_hthree_head_tag, $text_hfour_head_tag, $text_hfive_head_tag, $text_hsix_head_tag );
 foreach ( $cgss_condition_head_tags as $cgss_condition_head_tag ) {
  if ( $cgss_condition_head_tag == null ) {
   $cgss_condition_head_tags = '';
  }
 }

 //declare xpath and format it to get content
 $cgss_xpath = new DomXPath( $cgss_dom );
 $cgss_html_text_node = $cgss_xpath->query('//html');
 $cgss_html_text_node_size = strlen( $cgss_dom->saveHTML( $cgss_html_text_node->item(0) ) );


 foreach ( $cgss_xpath->query( '//script' ) as $entry) {
  $entry->parentNode->removeChild( $entry );
 }
 foreach ( $cgss_xpath->query( '//style' ) as $entry) {
  $entry->parentNode->removeChild( $entry );
 }
 $cgss_only_text_node = $cgss_xpath->query('//body[text()]')->item(0);
 $cgss_only_text = $cgss_only_text_node->nodeValue;

 //text html ratio
 $cgss_html_text_ratio_value = ( ( strlen( $cgss_only_text ) / ( $cgss_html_text_node_size - strlen( $cgss_only_text ) ) ) * 100 );
 $cgss_html_text_ratio = round( $cgss_html_text_ratio_value, 2 );

 //get title with dom document
 $cgss_title_node = $cgss_dom->getElementsByTagName( 'title' );
 $cgss_title_pre = $cgss_title_node->item(0);
 $cgss_title = $cgss_title_pre->nodeValue;

 //title result
 if ( $cgss_title != null ) {
  if ( strlen( $cgss_title ) <= 70 ) {
   $cgss_title_result = $cgss_title . '<br />' . __( 'This Web page\'s title is optimized.', 'cgss' );
   $cgss_title_action = __( 'No Action is Required', 'cgss' );
  } else {
   $cgss_title_result = $cgss_title . '<br />' . __( 'Characters', 'cgss' ) . '&nbsp;' . strlen( $cgss_title );
   $cgss_title_action = __( 'Please optimize your title for character limit 70', 'cgss' );
  }
 } else {
  $cgss_title_result = __( 'There is no web page title.', 'cgss' );
  $cgss_title_action = __( 'Please enter your webpage title.', 'cgss' );
 }

 //image result
 $cgss_image_result = __( 'Total number of images', 'cgss' ) . '&nbsp;' . $cgss_image_count . '<br />' . __( 'Use of "alt" attribute', 'cgss' ) . '&nbsp;' . $cgss_image_alt_count . '<br />' . __( 'Use of "height" attribute', 'cgss' ) . '&nbsp;' . $cgss_image_height_count . '<br />' . __( 'Use of "width" attribute', 'cgss' ) . '&nbsp;' . $cgss_image_width_count;
 if ( $cgss_image_alt_count != $cgss_image_count or $cgss_image_height_count != $cgss_image_count or $cgss_image_width_count != $cgss_image_count ) {
  $cgss_image_action = __( 'Please insert the missing attributes.', 'cgss' );
 } else {
  $cgss_image_action = __( 'No action is required.', 'cgss' );
 }

 //link numbers result
 $cgss_link_numbers_result = __( 'Number of Links', 'cgss' ) . '&nbsp;' . $cgss_link_count . '<br />' . __( 'Outgoing Links', 'cgss' ) . '&nbsp;' . $cgss_outgoing_link_count;
 if ( $cgss_link_count <= 50 ) {
  $cgss_link_numbers_action = __( 'No action is required.', 'cgss' );
 } elseif ( $cgss_link_count > 50 and $cgss_link_count < 100 ) {
  $cgss_link_numbers_result .= '<br />' . __( 'Number of Links are large.', 'cgss' );
  $cgss_link_numbers_action = __( 'Please try to reduce number of links.', 'cgss' );
 } elseif ( $cgss_link_count >= 100 ) {
  $cgss_link_numbers_result .= '<br />' . __( 'Number of Links are too much.', 'cgss' );
  $cgss_link_numbers_action = __( 'Number of Links should be lowered.', 'cgss' );
 }

 //link attribute result
 $cgss_link_attr_result = __( 'Links with rel attribute', 'cgss' ) . '&nbsp;' . $cgss_link_rel_count . '<br />' . __( 'Links with title attribute', 'cgss' ) . '&nbsp;' . $cgss_link_title_count . '<br />' . __( 'Links with nofollow rel attibute', 'cgss' ) . '&nbsp;' . $cgss_nofollow_link_count;
 $cgss_rel_percent = ( $cgss_link_rel_count / $cgss_link_count ) * 100;
 $cgss_title_percent = ( $cgss_link_title_count / $cgss_link_count ) * 100;
 if ( $cgss_rel_percent <= 25 or $cgss_title_percent <= 25 ) {
  $cgss_link_attr_action = __( 'Put "rel" and "title" attributes to links.', 'cgss' );
 } else {
  $cgss_link_attr_action = __( 'No action is required.', 'cgss' );
 }

 //Text hierarchy result
 if ( $text_head_tag_count == 0 ) {
  $cgss_text_hierarchy_result = __( 'No heading tags of h1, h2, h3, h4, h5, h6 are present in the webpage. So, there is no text hierarchy present.', 'cgss' );
  $cgss_text_hierarchy_action = __( 'Write your text in proper hierarchy.', 'cgss' );
 } elseif ( $text_head_tag_count == 6 ) {
  $cgss_text_hierarchy_result = __( 'All heading tags h1, h2, h3, h4, h5, h6 are present in the webpage. So, there is text hierarchy present.', 'cgss' );
  $cgss_text_hierarchy_action = __( 'No Action is Required', 'cgss' );
 } elseif ( $text_head_tag_count == 1 ) {
  $cgss_text_hierarchy_result = __( 'One heading tag is present in the webpage.', 'cgss' ) . '<br />' . __( 'Which is', 'cgss' ) . '&nbsp;' . $text_hone_head_tag . $text_htwo_head_tag .$text_hthree_head_tag . $text_hfour_head_tag . $text_hfive_head_tag .$text_hsix_head_tag;
  $cgss_text_hierarchy_action = __( 'Write your text in proper hierarchy.', 'cgss' );
 } elseif ( $text_head_tag_count > 1 and $text_head_tag_count < 6 ) {
  $cgss_text_hierarchy_result = __( 'Some heading tags are present in the webpage.', 'cgss' ) . '<br />' . __( 'They are', 'cgss' ) . '&nbsp;' . $text_hone_head_tag . $text_htwo_head_tag .$text_hthree_head_tag . $text_hfour_head_tag . $text_hfive_head_tag .$text_hsix_head_tag;
  $cgss_text_hierarchy_action = __( 'Make sure your text is in hierarchy. If it is not please make it so.', 'cgss' );
 }

 //autogenerated content result
 $cgss_generated_content_result = __( 'Following is the content, which is not auto generated. You can compare your actual page ( in your editor ) with this content to check if javascripts have generated more contents.', 'cgss' ) . '<br /><a id="pagecontent">' . __( 'Page content &darr;', 'cgss' ) . '</a>';
 $cgss_generated_content_action = __( 'No Action Required', 'cgss' );

 //Text HTML ratio results
 $cgss_text_html_ratio_result = __( 'Text to HTML ratio for this webpage is', 'cgss' ) . '&nbsp;' . $cgss_html_text_ratio . '&nbsp;%' . '</br>';
 if ( $cgss_html_text_ratio < 20 ) {
  $cgss_text_html_ratio_result .= __( 'Too much HTML in your webpage.', 'cgss' );
  $cgss_text_html_ratio_action = __( 'Write more text, atleast', 'cgss' ) . '&nbsp;' . ( 20 - $cgss_html_text_ratio ) . '&nbsp;% more';
 } elseif ( $cgss_html_text_ratio > 70 ) {
  $cgss_text_html_ratio_result .= __( 'Too much text in your webpage.', 'cgss' );
  $cgss_text_html_ratio_action = __( 'Make this page more appealing with more HTML. Or write less text, atleast', 'cgss' ) . '&nbsp;' . ( $cgss_html_text_ratio - 70 ) . '&nbsp;% less';;
 } else {
  $cgss_text_html_ratio_result .= __( 'This ratio for this webpage is normal.', 'cgss' );
  $cgss_text_html_ratio_action = __( 'No action is required.', 'cgss' );
 }

 //url results
 if ( strpos( $cgss_url, '?' ) == TRUE and strpos( $cgss_url, '=' ) == TRUE  ) {
  if ( strlen ( $cgss_url ) <= 255 ) {
   $cgss_url_result = __( 'Google may confuse this page as auto-generated. Please change your url type to plain text.', 'cgss' );
   $cgss_url_action = __( 'Change your url to different type of permalink', 'cgss' ) . ' <a href="' . home_url() . '/wp-admin/options-permalink.php" target="_blank">' . __( 'here', 'cgss' ) . '</a>';
  } else {
   $cgss_url_result = __( 'The webpage url is really bad, unusable. Google may consider it spam.', 'cgss' );
   $cgss_url_action = __( 'Change your url size and type at page or post editor. Make sure you are not using any session tracking.', 'cgss' );
  }
 } else {
  $cgss_url_result = __( 'Great! This webpage url maintains guidelines.', 'cgss' );
  $cgss_url_action = __( 'No action is required.', 'cgss' );
 }

 //http results
 $total_http_reqests = $cgss_image_count + $cgss_js_count + $cgss_css_count;
 $cgss_http_request_result = __( 'This webpage makes', 'cgss' ) . '&nbsp;' . $total_http_reqests . '&nbsp;' . __( 'HTTP requests.', 'cgss' );
 if ( $total_http_reqests <= 10 ) {
  $cgss_http_request_result .= __( '&nbsp;It\'s fine.', 'cgss' );
  $cgss_http_request_action = __( 'No action is required.', 'cgss' );
 } elseif ( $total_http_reqests > 10 and $total_http_reqests < 50 ) {
  $cgss_http_request_result .= __( '&nbsp;It should be less.', 'cgss' );
  $cgss_http_request_action = __( 'Try to make less HTTP requests.', 'cgss' ) . '<br /><br /><a id="directlinkSELL">' . __( 'We have a solution &darr;', 'cgss' ) . '</a>';
 } elseif ( 50 <= $total_http_reqests ) {
  $cgss_http_request_result .= __( '&nbsp;This is very high.', 'cgss' );
  $cgss_http_request_action = __( 'Try to make less HTTP requests', 'cgss' );
 }

 //meta description, meta robots, meta ogp results
 $cgss_meta_all = $cgss_dom->getElementsByTagName( 'meta' );
 foreach ( $cgss_meta_all as $cgss_meta ) {
  if ( $cgss_meta->getAttribute( 'name' ) == 'description' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_meta_desc = $cgss_meta->getAttribute( 'content' );
    $cgss_meta_desc_length = strlen( $cgss_meta_desc );
    $cgss_meta_desc_result = $cgss_meta_desc . '<br />&nbsp;' . __( 'Characters', 'cgss' ) . '&nbsp;:&nbsp;' . $cgss_meta_desc_length;
    if ( $cgss_meta_desc_length < 155) {
     $cgss_meta_desc_action = __( 'No action is required.', 'cgss' );
    } else {
     $cgss_meta_desc_action = __( 'Description is too long, write something within 155 characters. Altough it does not hamper ranking but it looks bad in search engine page result. Hence, may reduce click through rate (CTR).', 'cgss' );
    }
   } else {
    $cgss_meta_desc = __( 'No description can be found.', 'cgss' );
    $cgss_meta_desc_result = $cgss_meta_desc . '&nbsp;' . __( 'This is not good.', 'cgss' );
    $cgss_meta_desc_action = __( 'Please enter some meta description using any free plugin.', 'cgss' );
   }
  }
  if ( $cgss_meta->getAttribute( 'name' ) == 'canonical' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_meta_cano = $cgss_meta->getAttribute( 'content' );
    $cgss_meta_cano_result = $cgss_meta_cano;
    if ( $cgss_meta_cano == $cgss_url ) {
     $cgss_meta_cano_action = __( 'No action is required.', 'cgss' );
    } else {
     $cgss_meta_cano_action = __( 'Canonical URL of this webpage is different from its original url. Be sure you want your page to be indexed by canonical url.', 'cgss' );
    }
   } else {
    $cgss_meta_cano = __( 'No canonical link value can be found.', 'cgss' );
    $cgss_meta_cano_result = $cgss_meta_cano . '&nbsp;' . __( 'This is not good.', 'cgss' );
    $cgss_meta_cano_action = __( 'Please enter desired meta canonical link using any free plugin.', 'cgss' );
   }
  }
  if ( $cgss_meta->getAttribute( 'name' ) == 'robots' ) {
   $cgss_meta_robot_value = $cgss_meta->getAttribute( 'content' );
   if ( strpos( $cgss_meta_robot_value, 'noindex' ) or strpos( $cgss_meta_robot_value, 'nofollow' ) ) {
    $cgss_meta_robot_result = __( 'Meta robot is having noindex nofollow value. This is not good.', 'cgss' );
    $cgss_meta_robot_action = __( 'Uncheck', 'cgss' ) . '&nbsp;<a href="' . home_url() . '/wp-admin/options-reading.php/" target="_blank">' . __( 'Search Engine Visibility settings', 'cgss' ) . '</a>.' . __( 'If already unchecked see if your SEO plugin is generating meta robot. If so contact the plugin provider.', 'cgss' );
   } else {
    $cgss_meta_robot_result = __( 'Meta robot does not have noindex nofollow value.', 'cgss' );
    $cgss_meta_robot_action = __( 'No action is required.', 'cgss' );
   }
  }
  if ( $cgss_meta->getAttribute( 'property' ) == 'og:site_name' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_ogp_meta_site_name_content = $cgss_meta->getAttribute( 'content' );
    $cgss_ogp_meta_site_name_signal = '1';
   } else {
    $cgss_ogp_meta_site_name_content = 'BLANK';
   }
  }
  if ( $cgss_meta->getAttribute( 'property' ) == 'og:url' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_ogp_meta_url_content = $cgss_meta->getAttribute( 'content' );
    $cgss_ogp_meta_url_signal = '1';
   } else {
    $cgss_ogp_meta_site_name_content = 'BLANK';
   }
  }
  if ( $cgss_meta->getAttribute( 'property' ) == 'og:type' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_ogp_meta_type_content = $cgss_meta->getAttribute( 'content' );
    $cgss_ogp_meta_type_signal = '1';
   } else {
    $cgss_ogp_meta_site_name_content = 'BLANK';
   }
  }
  if ( $cgss_meta->getAttribute( 'property' ) == 'og:title' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_ogp_meta_title_content = $cgss_meta->getAttribute( 'content' );
    $cgss_ogp_meta_title_signal = '1';
   } else {
    $cgss_ogp_meta_site_name_content = 'BLANK';
   }
  }
  if ( $cgss_meta->getAttribute( 'property' ) == 'og:description' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_ogp_meta_desc_content = $cgss_meta->getAttribute( 'content' );
    $cgss_ogp_meta_desc_signal = '1';
   } else {
    $cgss_ogp_meta_site_name_content = 'BLANK';
   }
  }
  if ( $cgss_meta->getAttribute( 'property' ) == 'og:image' ) {
   if ( $cgss_meta->getAttribute( 'content' ) != null ) {
    $cgss_ogp_meta_image_content = $cgss_meta->getAttribute( 'content' );
    $cgss_ogp_meta_image_signal = '1';
   } else {
    $cgss_ogp_meta_site_name_content = 'BLANK';
   }
  }
 }

 if ( $cgss_meta_desc_result == null or $cgss_meta_desc_action == null ) {
  $cgss_meta_desc_result = __( 'No Meta description is found.', 'cgss' );
  $cgss_meta_desc_action = __( 'Please enter a meta description tag. You can use any free SEO plugins available to do so.', 'cgss' );
 }

 if ( $cgss_meta_cano_result == null or $cgss_meta_cano_action == null ) {
  $cgss_meta_cano_result = __( 'No canonical link meta tag can be found.', 'cgss' );
  $cgss_meta_cano_action = __( 'Please enter desired meta canonical link using any free plugin.', 'cgss' );
 }

 if ( $cgss_meta_robot_result == null or $cgss_meta_robot_action == null ) {
  $cgss_meta_robot_result = __( 'No Meta robot is found.', 'cgss' );
  $cgss_meta_robot_action = __( 'No action is required.', 'cgss' );
 }

 if ( $cgss_ogp_meta_site_name_signal == '1' ) {
  $cgss_ogp_meta_site_name = 'Site Name' . '&nbsp;:&nbsp;' . $cgss_ogp_meta_site_name_content;
 } else {
  $cgss_ogp_meta_site_name = __( 'No website name found. It may be', 'cgss' ) . '&nbsp;' . get_bloginfo( 'name' );
 }
 if ( $cgss_ogp_meta_url_signal == '1' ) {
  $cgss_ogp_meta_url = 'Sharing URL' . '&nbsp;:&nbsp;' . $cgss_ogp_meta_url_content;
 } else {
  $cgss_ogp_meta_url = __( 'No sharing url found. It may be', 'cgss' ) . '&nbsp;' . $cgss_url;
 }
 if ( $cgss_ogp_meta_type_signal == '1' ) {
  $cgss_ogp_meta_type = 'Type' . '&nbsp;:&nbsp;' . $cgss_ogp_meta_type_content;
 } else {
  $cgss_ogp_meta_type = __( 'No application type name found. It may be "website" or "blog".', 'cgss' );
 }
 if ( $cgss_ogp_meta_title_signal == '1' ) {
  $cgss_ogp_meta_title = 'Title' . '&nbsp;:&nbsp;' . $cgss_ogp_meta_title_content;
 } else {
  $cgss_ogp_meta_title = __( 'No sharing title was found.', 'cgss' );
 }
 if ( $cgss_ogp_meta_desc_signal == '1' ) {
  $cgss_ogp_meta_desc = 'Description' . '&nbsp;:&nbsp;' . $cgss_ogp_meta_desc_content;
 } else {
  $cgss_ogp_meta_desc = __( 'No sharing description was found.', 'cgss' );
 }
 if ( $cgss_ogp_meta_image_signal == '1' ) {
  $cgss_ogp_meta_image = 'Image Link' . '&nbsp;:&nbsp;' . $cgss_ogp_meta_image_content;
 } else {
  $cgss_ogp_meta_image = __( 'No image was found.', 'cgss' );
 }
 if ( $cgss_ogp_meta_site_name_signal == '1' and $cgss_ogp_meta_url_signal == '1' and $cgss_ogp_meta_type_signal == '1' and $cgss_ogp_meta_title_signal == '1' and $cgss_ogp_meta_desc_signal == '1' and $cgss_ogp_meta_desc_signal == '1' and $cgss_ogp_meta_image_signal == '1' ) {
  $cgss_meta_ogp_result = __( 'Following are the meta tags for OGP.', 'cgss' ) . '<br />' . $cgss_ogp_meta_site_name . '<br />' . $cgss_ogp_meta_url . '<br />' . $cgss_ogp_meta_type . '<br />' . $cgss_ogp_meta_title . '<br />' . $cgss_ogp_meta_desc . '<br />' . $cgss_ogp_meta_image;
   $cgss_meta_ogp_action = __( 'No Action Required.', 'cgss' );
 } elseif ( $cgss_ogp_meta_site_name_signal != '1' and $cgss_ogp_meta_url_signal != '1' and $cgss_ogp_meta_type_signal != '1' and $cgss_ogp_meta_title_signal != '1' and $cgss_ogp_meta_desc_signal != '1' and $cgss_ogp_meta_desc_signal != '1' and $cgss_ogp_meta_image_signal != '1' ) {
  $cgss_meta_ogp_result = __( 'No Meta tags for Open Graph Protocol ( OGP ) is found. ALtough these are not ranking factors but they increases chances better social sharing if used properly.', 'cgss' );
  $cgss_meta_ogp_action = __( 'Please enter meta tags for following properties using a free plugin. og:site_name, og:type, og:title, og:description, og:image.', 'cgss' );
 } else {
  $cgss_meta_ogp_result = __( 'Following are the meta tags for OGP.', 'cgss' ) . '<br />' . $cgss_ogp_meta_site_name . '<br />' . $cgss_ogp_meta_url . '<br />' . $cgss_ogp_meta_type . '<br />' . $cgss_ogp_meta_title . '<br />' . $cgss_ogp_meta_desc . '<br />' . $cgss_ogp_meta_image;
  $cgss_meta_ogp_action = __( 'Please enter remaining meta OGP tags, using any free SEO plugin.', 'cgss' );
 }

 //If-Modified-Since results
 if ( array_key_exists( 'Last-Modified', $cgss_headers ) ) {
  $if_mod_since_result = __( 'Great! If-Modified-Since header is On.', 'cgss' );
  $if_mod_since_action = __( 'No action is required.', 'cgss' );
 } else {
  $if_mod_since_result = __( 'If-Modified-Since header is Off', 'cgss' );
  $if_mod_since_action = __( 'Ask your hosting provider to activate If-Modified-Since header.', 'cgss' );
 }

 //robots.txt results
 if ( $cgss_robot_command != 'cgss-robots-command' ) {
  $cgss_robot_result = __( 'You have not approved this checking while filling up scaning form.', 'cgss' );
  $cgss_robot_action = __( 'If you are not sure about your robots.txt blocking, you can recheck this url while checking corrosponding checkbox.', 'cgss' );
 } else {
  if ( strpos( $cgss_robot_file_headers[0], '404 Not Found' ) ) {
   $cgss_robot_result = __( 'Your website do not have a robots.txt file. So, this parameter can not be judged.', 'cgss' );
   $cgss_robot_action = __( 'Create a robots.txt file using suitable free seo plugins available.', 'cgss' );
  } else {
   $cgss_robots_inside_url = explode( home_url() . '/', $cgss_url );
   $cgss_robots_search_string = $cgss_robots_inside_url[1];
   if ( strpos( $cgss_url, $cgss_robots_inside_url[1] ) ) {
    $cgss_robot_result = __( 'This url may be blocked for search engines. Wnd part of the url is found in', 'cgss' ) . '<a href="'. home_url() . '/robots.txt/" target="_blank">' . __( 'robots.txt file', 'cgss' ) . '</a>';
    $cgss_robot_action = __( 'See the file and be sure that it is not disallowing the url.', 'cgss' );
   } else {
    $cgss_robot_result = __( 'This url is not blocked for search engines by robots.txt file.', 'cgss' );
    $cgss_robot_action = __( 'No action is required.', 'cgss' );
   }
  }
 }

 //sitemap.xml results
 if ( $cgss_sitemap_input != null ) {
  if ( strpos( $cgss_sitemap_file_headers[0], '404 Not Found' ) ) {
   $cgss_sitemap_result = __( 'Your website do not have a sitemaps.txt file at the url you specified, which is', 'cgss' ) . '<a href="' . $cgss_sitemap_input . '" target="_blank">' . $cgss_sitemap_input . '</a>';
   $cgss_sitemap_action = __( 'Please give us correct sitemap.xml file url, next time you scan webpages.', 'cgss' );
  } else {
   $cgss_sitemaps_inside_url = explode( $cgss_sitemap_file, $cgss_sitemap_input );
   $cgss_sitemaps_search_string = $cgss_sitemaps_inside_url[1];
   if ( ! strpos( $cgss_sitemap_file, $cgss_url ) ) {
    $cgss_sitemap_result = __( 'This webpage url is not found in xml sitemap you entered.', 'cgss' ) . '&nbsp;<a href="' . $cgss_sitemap_input . '" target="_blank">' . $cgss_sitemap_input . '</a>';
    $cgss_sitemap_action = __( 'Please enlist your webpage url in sitemap.xml file which is submitted to search engines.', 'cgss' );
   } else {
    $cgss_sitemap_result = __( 'This url is in sitemaps.xml file.', 'cgss' );
    $cgss_sitemap_action = __( 'No action is required.', 'cgss' );
   }
  }
 } else {
  $cgss_sitemap_result = __( 'You have not approved sitemap.xml checking while filling up scanning form.', 'cgss' );
  $cgss_sitemap_action = __( 'You can input sitemap.xml url before scanning again.', 'cgss' );
 }

 //rich snippet results
 $cgss_htmls = $cgss_dom->getElementsByTagName( 'html' );
 foreach ( $cgss_htmls as $cgss_html ) {
  $cgss_html_rich_snippet_itemtype = $cgss_html->getAttribute( 'itemtype' );
  if ( substr( $cgss_span_rich_snippet_itemtype, 0, strlen( 'http://schema.org/' ) ) == 'http://schema.org/' ) {
   $cgss_type_rich_snippet = substr( $cgss_html_rich_snippet_itemtype, strlen( 'http://schema.org/' ) );
   $cgss_rich_snippet_result = __( 'Microdata rich snippet is present at this webpage.', 'cgss' ) . '<br />' . __( 'Type of rich snippet is', 'cgss' ) . '&nbsp;' . $cgss_type_rich_snippet;
   $cgss_rich_snippet_action = __( 'No action is required.', 'cgss' );
   break;
  }
 }
 if ( $cgss_type_rich_snippet == null ) {
  $cgss_bodys = $cgss_dom->getElementsByTagName( 'body' );
  foreach ( $cgss_bodys as $cgss_body ) {
   $cgss_body_rich_snippet_itemtype = $cgss_body->getAttribute( 'itemtype' );
   if ( substr( $cgss_span_rich_snippet_itemtype, 0, strlen( 'http://schema.org/' ) ) == 'http://schema.org/' ) {
    $cgss_type_rich_snippet = substr( $cgss_body_rich_snippet_itemtype, strlen( 'http://schema.org/' ) );
    $cgss_rich_snippet_result = __( 'Microdata rich snippet is present at this webpage.', 'cgss' ) . '<br />' . __( 'Type of rich snippet is', 'cgss' ) . '&nbsp;' . $cgss_type_rich_snippet;
    $cgss_rich_snippet_action = __( 'No action is required.', 'cgss' );
    break;
   }
  }
 }
 if ( $cgss_type_rich_snippet == null ) {
  $cgss_divs = $cgss_dom->getElementsByTagName( 'div' );
  foreach ( $cgss_divs as $cgss_div ) {
   $cgss_div_rich_snippet_itemtype = $cgss_div->getAttribute( 'itemtype' );
   if ( substr( $cgss_span_rich_snippet_itemtype, 0, strlen( 'http://schema.org/' ) ) == 'http://schema.org/' ) {
    $cgss_type_rich_snippet = substr( $cgss_div_rich_snippet_itemtype, strlen( 'http://schema.org/' ) );
    $cgss_rich_snippet_result = __( 'Microdata rich snippet is present at this webpage.', 'cgss' ) . '<br />' . __( 'Type of rich snippet is', 'cgss' ) . '&nbsp;' . $cgss_type_rich_snippet;
    $cgss_rich_snippet_action = __( 'No action is required.', 'cgss' );
    break;
   }
  }
 }
 if ( $cgss_type_rich_snippet == null ) {
  $cgss_spans = $cgss_dom->getElementsByTagName( 'span' );
  foreach ( $cgss_spans as $cgss_span ) {
   $cgss_span_rich_snippet_itemtype = $cgss_span->getAttribute( 'itemtype' );
   if ( substr( $cgss_span_rich_snippet_itemtype, 0, strlen( 'http://schema.org/' ) ) == 'http://schema.org/' ) {
    $cgss_type_rich_snippet = substr( $cgss_span_rich_snippet_itemtype, strlen( 'http://schema.org/' ) );
    $cgss_rich_snippet_result = __( 'Microdata rich snippet is present at this webpage.', 'cgss' ) . '<br />' . __( 'Type of rich snippet is', 'cgss' ) . '&nbsp;' .        $cgss_type_rich_snippet;
    $cgss_rich_snippet_action = __( 'No action is required.', 'cgss' );
    break;
   }
  }
 }
 if ( $cgss_type_rich_snippet == null ) {
  $cgss_rich_snippet_result = __( 'There is no microdata rich snippet present in this webpage.', 'cgss' );
  $cgss_rich_snippet_action = __( 'Use any free SEO plugin to markup your webpage with microdata rich snippet.', 'cgss' );
 }

 //cms results
 if ( get_bloginfo( 'version' ) != null ) {
  $cgss_cms_result = __( 'You are using WordPress version', 'cgss' ) . '&nbsp;:&nbsp;' . get_bloginfo( 'version' ) . '<br />' . __( 'This cms has no SEO optimization problem.', 'cgss' );
  $cgss_cms_action = __( 'No action is required.', 'cgss' );
 } else {
  $cgss_cms_result = __( 'You are not using WordPress, most probably it\'s a modified cms with wordpress core. We can not check it\'s current status of SEO compatibility.', 'cgss' );
  $cgss_cms_action = __( 'Please use proper WordPress software from', 'cgss' ) . '&nbsp;<a href="http://wordpress.org/" target="_blank">' . __( 'Wordpress website', 'cgss' ) . '</a>';
 }

 //caching results
 if ( array_key_exists( 'Cache-Control', $cgss_headers ) ) {
  $cgss_caching_result = __( 'Cache is active. This webpage has following cache parameters.', 'cgss' ) . '<br />' . $cgss_headers['Cache-Control'];
  $cgss_caching_action = __( 'No action is required.', 'cgss' );
 } else {
  $cgss_caching_result = __( 'Cache is inactive. This webpage don\'t returns cache parameters.', 'cgss' );
  $cgss_caching_action = __( 'Pse cache method for better user experience. Use any free cache plugin available.', 'cgss' );
 }

 //Keep Alive results
 if ( $cgss_headers['Connection'] != 'Keep-Alive' ) {
  $keep_alive_result = __( 'Keep Alive Connection is Off', 'cgss' );
  $keep_alive_action = __( 'Ask your hosting provider to activate Keep Alive Connection.', 'cgss' );
 } else {
  $keep_alive_result = __( 'Great! Keep Alive Connection On', 'cgss' );
  $keep_alive_action = __( 'No action is required.', 'cgss' );
 }

 //page content size results
 $cgss_page_content_result = __( 'Total size of webpage', 'cgss' ) . '&nbsp;' . '&nbsp;' . __( 'kb.', 'cgss' ) . '&nbsp;' . __( 'Comaritive content size is following.', 'cgss' ) . '<br />' . __( 'Size of css', 'cgss' ) . '&nbsp;' . $cgss_style_link_size . '&nbsp;' . __( 'kb.', 'cgss' ) . '<br />' . __( 'Size of javascripts', 'cgss' ) . '&nbsp;' . $cgss_js_size . '&nbsp;' . __( 'kb.', 'cgss' );
 $cgss_page_content_action = __( 'No action required.', 'cgss' );

 //promo data showing
 $cgss_solve_data = '?theme=' . $cgss_theme_name . '&url=' . home_url();

 //scan time counter stop
 $cgss_scan_time = microtime();
 $cgss_scan_time = explode(' ', $cgss_scan_time);
 $cgss_scan_time = $cgss_scan_time[1] + $cgss_scan_time[0];
 $cgss_scan_finish = $cgss_scan_time;
 $total_scan_time = round(($cgss_scan_finish - $cgss_scan_start), 4);
 $cgss_scan_time_consumed = $total_scan_time;

 $cgss_scaned_page_props = array(
	array( __( 'Website Name', 'cgss' ), get_bloginfo( 'name' ) . '&nbsp;(&nbsp;' . home_url() . '&nbsp;)&nbsp;' ),
	array( __( 'Scanned Webpage URL', 'cgss' ), $cgss_url ),
	array( __( 'Theme Name', 'cgss' ), $cgss_theme_name ),
	array( __( 'Scanned for', 'cgss' ), __( 'Search Engine Compatibility (Standard is Google Webmaster Guidelines)', 'cgss' ) ),
	array( __( 'Time Taken', 'cgss' ), $cgss_scan_time_consumed . '&nbsp;' . __( 'seconds for Scan', 'cgss' ) . '&nbsp;and&nbsp;' . $cgss_load_time_consumed . '&nbsp;' . __( 'seconds for loading components', 'cgss' ) ),
	array( __( 'Note', 'cgss' ), __( 'If-Modified-Since, CMS, Caching and Keep Alive these 4 features are applicable to whole website. That means,', 'cgss' ) . '<br />&nbsp;&nbsp;&nbsp;&nbsp;' . __( 'if you find any of them wrong here they are wrong for every webpage in your website.', 'cgss' ) ),
 );
 $cgss_content_results = array (
	array( __( 'Title', 'cgss' ), __( 'This tells crawling robot about the content of the website. This should be descriptive and better be within 70 characters.', 'cgss' ), $cgss_title_result, $cgss_title_action ),
	array( __( 'Images', 'cgss' ), __( 'Usage of images must be optimized to balance visual look and amount of text. Used images should not be resized, should be height width specified and should have alt attribute. This will ensure better indexing and less document load time.', 'cgss' ), $cgss_image_result, $cgss_image_action ),
	array( __( 'Text Hierarchy', 'cgss' ), __( 'In a standard webpage text should be presented in a hierarchy of titles and body text. Proper use of h1, h2, h3, h4, h5, h6 tags ensures easier crawling.', 'cgss' ), $cgss_text_hierarchy_result, $cgss_text_hierarchy_action ),
	array( __( 'Auto generated Content', 'cgss' ), __( 'Content not written in html, but generated by javascript is not considered a good thing. Altough we have to use social, advertisement, feed, blogroll and other contents, these should be kept less in numbers.', 'cgss' ), $cgss_generated_content_result, $cgss_generated_content_action ),
	array( __( 'text/html Ratio', 'cgss' ), __( 'This ratio is important because it indicates that the webpage is content rich. Always try to keep it between 25 % to 70 %. This is not a ranking factor but good practice for less document load time.', 'cgss' ), $cgss_text_html_ratio_result, $cgss_text_html_ratio_action ),
	array( __( 'Link Numbers', 'cgss' ), __( 'Number of links plays an significant role in deciding pagerank. The numbers should be optimized and should not be very high.', 'cgss' ), $cgss_link_numbers_result, $cgss_link_numbers_action ),
	array( __( 'Link Attributes', 'cgss' ), __( 'There are 2 attributes "rel" and "title" for any webpage links. They defines the link and it\'s function.', 'cgss' ), $cgss_link_attr_result, $cgss_link_attr_action ),
 );
 $cgss_tech_results = array (
  array( __( 'URL', 'cgss' ), __( 'This is address to your webpage. Search engines index your webpage content against this address. If this address has variable informations then search engines will consider it bad. Make sure your url size is less than 255 bytes, otherwise search engines consider it as spam.', 'cgss' ), $cgss_url_result, $cgss_url_action ),
  array( __( 'HTTP Requests', 'cgss' ), __( 'This is number of HTTP requests your web page makes to fetch content from server. This influences document load time, because more requests will call for more downloads. So, more is spent in downloading content.', 'cgss' ), $cgss_http_request_result, $cgss_http_request_action ),
  array( __( 'Meta Description ', 'cgss' ), __( 'Meta description tag tells search engine robots about the webpage. It must be present and optimal length should be 155 characters. It is not a ranking factor, but it should be there to describe about your page.', 'cgss' ), $cgss_meta_desc_result, $cgss_meta_desc_action ),
    array( __( 'Meta Canonical ', 'cgss' ), __( 'Canonicalization is the process of picking the best url when there are several choices. This meta tag "canonical" helps search engine crawlers to index a page which can be approached from different urls (like http://www.example.com/ and http://example.com/).', 'cgss' ), $cgss_meta_cano_result, $cgss_meta_cano_action ),
  array( __( 'Meta Robots', 'cgss' ), __( 'Meta robots tag present at head section may request search engine robots to crawl or leave the webpage. If given a value of noindex and nofollow, webpage will not be indexed.', 'cgss' ), $cgss_meta_robot_result, $cgss_meta_robot_action ),
  array( __( 'Open Graph Protocol', 'cgss' ), __( 'These meta tags are used to carry values, whenever you share your webpage in social networks, they send webpage information. Useful in social media representation and has indirect influence on SEO.', 'cgss' ), $cgss_meta_ogp_result, $cgss_meta_ogp_action ),
  array( __( 'If-Modified-Since', 'cgss' ), __( 'This is a server feature. This automatically Informs search engine robots, whenever any change takes place in webpage content.', 'cgss' ), $if_mod_since_result, $if_mod_since_action ),
  array( __( 'robots.txt', 'cgss' ), __( 'This is a simple text file kept in website root directory. Search engine robot crawlers read this file to know which webpage will not be crawled. It should be here', 'cgss' ) . ' <a href="' . home_url() . '/robots.txt/' . '" target="_blank">' . home_url() . '/robots.txt/' . '</a> ' . __( 'Make sure robots are not blocking crawlers to come to this page.', 'cgss' ), $cgss_robot_result, $cgss_robot_action ),
  array( __( 'sitemap.xml', 'cgss' ), __( 'This is a xml file of sitemap. Search engine robot crawlers read this file to know which webpage to be indexed. Make sure your webpage url is enlisted in this file.', 'cgss' ), $cgss_sitemap_result, $cgss_sitemap_action ),
  array( __( 'Rich Snippet', 'cgss' ), __( 'This is a type of HTML markup which helps search engine pages to render search results. Google encourages it\'s use because this special search page formating increase click through rate (CTR).', 'cgss' ) . '' . __( 'We will search for microdata type markup as it is recommended by Google.', 'cgss' ), $cgss_rich_snippet_result, $cgss_rich_snippet_action ),
  array( __( 'CMS', 'cgss' ), __( 'Content Management System (cms) creates webpage from scripts kept in server. So, Google crawlers may consider this contents to be auto generated and may not index them. But, for WordPress users this is not a problem.', 'cgss' ), $cgss_cms_result, $cgss_cms_action ),
  array( __( 'Caching', 'cgss' ), __( 'Caching html, css, javascripts, images in users browsers can be very useful in reducing document load time. Search engine crawlers likes webpages with better document load time.', 'cgss' ), $cgss_caching_result, $cgss_caching_action ),
  array( __( 'Keep Alive', 'cgss' ), __( 'This is a server feature. This keeps the connection between server and browser active for a said amount of time. And saves time for excess communication. Hence it lowers document load time which is better.', 'cgss' ), $keep_alive_result, $keep_alive_action ),
);
?>
