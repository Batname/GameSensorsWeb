<?php
if (!class_exists('googlefonts')) {
    class googlefonts {
        //This is where the class variables go, don't forget to use @var to tell what they're for
        /**
        * @var string The options string name for this plugin
        */
        var $optionsName = 'googlefonts_options';
        /**
        * @var string $localizationDomain Domain used for localization
        */
        var $localizationDomain = "googlefonts";
        /**
        * @var string $pluginurl The path to this plugin
        */
        var $thispluginurl = '';
        /**
        * @var string $pluginurlpath The path to this plugin
        */
        var $thispluginpath = '';
        /**
        * @var array $options Stores the options for this plugin
        */
        var $options = array();
        var $api_key = '?key=AIzaSyDXgT0NYjLhDmUzdcxC5RITeEDimRmpq3s';
        var $api_url = 'https://www.googleapis.com/webfonts/v1/webfonts';
        var $gf_data_option_name = "googlefonts_data";
        var $gf_fonts_file = 'webfonts.php';
        var $gf_notices = array();
        var $gf_filename = 'google-fonts.php';
        var $gf_usage_elements = array(
                    'body' => 'All (body tags)',
                    'navbar' => 'Navbar',
                    'heading1' => 'Headline 1 (h1 tags)',
                    'heading2' => 'Headline 2 (h2 tags)',
                    'heading3' => 'Headline 3 (h3 tags)',
                    'heading4' => 'Headline 4 (h4 tags)',
                    'heading5' => 'Headline 5 (h5 tags)',
                    'heading6' => 'Headline 6 (h6 tags)',
                    'blockquote' => 'Blockquotes',
                    'p' => 'Paragraphs (p tags)',
                    'li' => 'Lists (li tags)'
                );
        var $gf_usage_elements_map = array(
                    'body' => 'body',
                    'navbar' => '.navbar',
                    'heading1' => 'h1',
                    'heading2' => 'h2',
                    'heading3' => 'h3',
                    'heading4' => 'h4',
                    'heading5' => 'h5',
                    'heading6' => 'h6',
                    'blockquote' => 'blockquote',
                    'p' => 'p',
                    'li' => 'li'
                );
        // for backwards compatability: main font name => css name
        var $gf_element_names = array(
            'googlefonts_font1' => 'googlefont1',
            'googlefonts_font2' => 'googlefont2',
        );
        var $font_styles_translation = array(
            '100' => 'Ultra-Light',
            '200' => 'Light',
            '300' => 'Book',
            '400' => 'Normal',
            '500' => 'Medium',
            '600' => 'Semi-Bold',
            '700' => 'Bold',
            '800' => 'Extra-Bold',
            '900' => 'Ultra-Bold',
            'regular' => 'Normal 400'
        );
        var $gf_fonts = array();
        /**
        * PHP 5 Constructor
        */
        function __construct(){
            //"Constants" setup
            $this->thispluginurl = WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';
            $this->thispluginpath = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)).'/';
            //Initialize the options
            //This is REQUIRED to initialize the options when the plugin is loaded!
            $this->getOptions();
            //Load the list of fonts from the Google API or local cache
            $this->gf_fonts = get_option($this->gf_data_option_name);
            //Convert the options from pre v3.0 array
            $this->gf_convert_fonts();
            //Actions
            add_action("admin_menu", array(&$this,"admin_menu_link"));
            add_action('admin_enqueue_scripts',array(&$this,'gf_admin_scripts'));
            add_action('wp_enqueue_scripts',array(&$this, 'googlefontsstart'));
            add_action("wp_head", array(&$this,"addgooglefontscss"));
            add_action('wp_ajax_googlefont_action', array($this, 'googlefont_action_callback'));
        }
        /***********************************************/
        function gf_admin_scripts(){
        }
        function gf_print_notices(){
            $out = null;
            if($this->gf_notices){
                $out.= "<ul class='notices'>\n";
                foreach($this->gf_notices as $notice){
                    $out.= "<li class='notice'>".$notice."</li>\n";
                }
                $out.= "</ul>\n";
            }
            return $out;
        }
        function gf_get_font_file(){
            $fonts = null;
            $fonts_object = null;
            $this->gf_check_font_cache();
            $json = get_option($this->gf_data_option_name);
            if($json){
                $fonts_object = json_decode($json);
            }
            if($fonts_object && is_object($fonts_object)){
                if(isset($fonts_object->error) && $fonts_object->error){
                    $this->gf_notices[] = sprintf(esc_html__('Google API Error: %s. %s', 'blackfyre'), $fonts_object->error->code, $fonts_object->error->message);
                }
                if($fonts_object->items && is_array($fonts_object->items)){
                    $fonts = $fonts_object->items;
                }
            }
            $this->gf_fonts = $fonts;
        }
        function gf_check_font_cache(){
            $result = false;
            if($this->gf_fonts){
                /* 60 seconds x 60 minutes x 12 hours */
                $filetime = $this->options['googlefont_data_time'];
                if(time() >= ($filetime + (60 * 60 * 12))){
                    if($this->gf_update_font_cache()){
                        $this->gf_notices[] = sprintf(esc_html__("Font list sync successful, updated %s.", 'blackfyre'), $this->gf_font_list_time());
                        $result = true;
                    }else{
                        $this->gf_notices[] = sprintf(esc_html__("Unable to do a live update on the font list. Using cached version from %s.", 'blackfyre'), $this->gf_font_list_time($filetime));
                    }
                }else{
                    $this->gf_notices[] = sprintf(esc_html__("Font list is up to date. Last updated %s.", 'blackfyre'), $this->gf_font_list_time($filetime));
                }
            }else{
                if($this->gf_update_font_cache()){
                    $this->gf_notices[] = sprintf(esc_html__("Font list sync successful, created %s.", 'blackfyre'), $this->gf_font_list_time());
                    $result = true;
                }else{
                    $this->gf_notices[] = esc_html__("Font list file doesn't exist and wasn't able to be updated.", 'blackfyre');
                }
            }
            return $result;
        }
        function gf_font_list_time($filetime=null){
            if(!$filetime){
                $filetime = $this->options['googlefont_data_time'];
            }
            $offset = (int)get_option('gmt_offset') * 60 * 60;
            return (date('F j, Y, G:i:s',($filetime + $offset)));
        }
        function gf_update_font_cache(){
            $updated = false;
            $fonts_json = $this->gf_download_font_list($this->api_url);
            /* if we didn't get anything, try with api key */
            if(!$fonts_json){
                $fonts_json = $this->gf_download_font_list($this->api_url.$this->api_key);
            }
            /* if still nothing and do not have a cache already, then get the local file instead */
            if(!$fonts_json && !$this->gf_fonts){
                $fonts_json = $this->gf_get_local_fonts();
            }
            if($fonts_json){
                /* put into option in WordPress */
                $updated = update_option($this->gf_data_option_name,$fonts_json);
            }
            return $updated;
        }
        function gf_download_font_list($url){
            $fonts_json = null;
            if(function_exists('wp_remote_get')){
                $response = wp_remote_get($url, array('sslverify' => false));
                if( is_wp_error($response)){
                    $this->gf_notices[] = sprintf(esc_html__("Unable to connect to Google's Webfont server at <a href='%s' target='_blank'>this URL</a>.", 'blackfyre'), $url);
                    foreach($response->errors as $error){
                        foreach($error as $message){
                            $this->gf_notices[] = $message;
                        }
                    }
                }else{
                    /* see if the response has an error */
                    if(isset($response['body']) && $response['body']){
                        if(strpos($response['body'], 'error') === false){
                            /* no errors, good to go */
                            $fonts_json = $response['body'];
                            /* update the last updated time */
                            $this->options['googlefont_data_time'] = time();
                            /* save the options */
                            $this->saveAdminOptions();
                        }else{
                            $error = json_decode($response['body']);
                            $this->gf_notices[] = '<span class="slight">' . sprintf(esc_html__('Google API Notice: %s. %s', 'blackfyre'), $error->error->code, $error->error->message) . '</span>';
                        }
                    }
                }
            }
            return $fonts_json;
        }
        function gf_get_local_fonts(){
            $fonts = null;
            include $this->gf_fonts_file;
            if($fonts){
                $this->gf_notices[] = esc_html__("Using the local font list file because we could not connect with Google.", 'blackfyre');
            }else{
                $this->gf_notices[] = esc_html__("Local font list file cannot be found or does not exist.", 'blackfyre');
            }
            return $fonts;
        }
        function gf_get_fonts_select($name="googlefont"){
            //prefill all the select boxes because there's not as much overhead there.
            $out = null;
            if($this->gf_fonts && is_array($this->gf_fonts)){
                $variants= null;
                $subsets = null;
                $first = true;
                $current_selection = $this->options['googlefont_selections'][$name]['family'];
                $out .= "<div id='".$name."' class='googlefont-block'>\n";
                $out .= "<select name='".$name."[family]' id='".$name."-select' class='webfonts-select'>\n";
                foreach($this->gf_fonts as $font){
                    $class = array();
                    $has_variants = false;
                    $has_subsets = false;
                    $normalized_name = $this->gf_normalize_font_name($font->family);
                    $is_selection = false;
                    if($normalized_name == $current_selection){
                        $is_selection = true;
                    }
                    $class[] = $normalized_name;
                    if(count($font->variants)>1){
                        $class[] = "has-variant";
                    }
                    if(count($font->subsets)>1){
                        $class[] = "has-subsets";
                    }
                    /* write the blank and 'none options on first pass */
                    if($first){
                        $first = false;
                        $out.="<option value=''></option>\n";
                        $out.="<option class='".implode(" ",$class)."' value='off' ".
                                $this->gf_is_selected($normalized_name, $current_selection).
                                ">" . esc_html__("None (Turn off this font)", 'blackfyre') . "</option>\n";
                    }
                    /* write the option */
                    $out.="<option class='".implode(" ",$class)."' value='".$normalized_name.
                            "' ".$this->gf_is_selected($normalized_name, $current_selection).">" . $font->family . "</option>\n";
                    if($is_selection){
                        /* get the font family variants */
                        $variants = $this->gf_get_font_variants($name, $font, $is_selection);
                        /* get the font character type subsets */
                        $subsets = $this->gf_get_font_subsets($name, $font, $is_selection);
                    }
                }
                $out.= "</select>\n";
                //if a font is already selected, get all of its details
                //otherwise, create a blank input for each.
                if(!$variants && !$subsets){
                    $variants = '<input type="checkbox" name="'.$name.'[variants][]" value="regular" class="check '.$normalized_name.' blank"> <label>Normal 400</label>';
                    $subsets = '<input type="checkbox" name="'.$name.'[subsets][]" value="latin" class="check '.$normalized_name.' blank"> <label>Latin</label>';
                }
                if($current_selection && $current_selection != 'off'){
                    $out .= '<a href="#'.$name.'-select" class="show_hide" id="show_hide-'.$name.'">' . esc_html__('Show Options', 'blackfyre') . '</a>';
                }
                /* add an ajax message div to indicate loading/waiting message or image */
                $out.="<div class='webfonts-waiting'></div>\n";
                /* add a section for additional selections */
                $out.= $this->gf_get_font_selections($name, $variants, $subsets);
                $out.='<div style="clear:both;"><input class="button-primary" type="submit" name="googlefonts_save" value="' . esc_html__('Save All Fonts', 'blackfyre') . '" /></div>';
                $out.="</div>\n";
            }
            return $out;
        }
        function gf_get_font_subsets($name, $font, $is_selection=false){
            $subsets = null;
            if($font && isset($font->subsets)){
                $normalized_name = $this->gf_normalize_font_name($font->family);
                $has_subsets = false;
                if(count($font->subsets)>1){
                    $has_subsets = true;
                }
                krsort($font->subsets);
                $subsets .= "<div class='subset-".$normalized_name." subset-items'>\n";
                $sid = null;
                foreach($font->subsets as $subset){
                    $sid = $this->gf_normalize_font_name($font->family . " " . $subset);
                    $schecked = null;
                    $readonly = null;
                    if($is_selection){
                        $schecked = $this->gf_is_checked($subset, $this->options['googlefont_selections'][$name]['subsets']);
                    }
                    if($is_selection && !$has_subsets){
                        $readonly = " readonly='readonly'";
                    }
                    $subsets .= '<input type="checkbox" id="'.$sid.'" name="'.$name.'[subsets][]" value="'.
                                    $subset.'" class="check ' . $normalized_name.'"'. $schecked . $readonly . '> <label for="'.$sid.
                                    '">' . ucwords($subset) . '</label><br>';
                }
                $subsets .= "</div>\n";
            }
            return $subsets;
        }
        function gf_get_font_variants($name, $font=null, $is_selection=false){
            $variants = null;
            if($font && isset($font->variants)){
                $normalized_name = $this->gf_normalize_font_name($font->family);
                $has_variants = false;
                if(count($font->variants)>1){
                    $has_variants = true;
                }
                ksort($font->variants);
                $variants .= "<div class='variant-".$normalized_name." variant-items'>\n";
                $vid = null;
                foreach($font->variants as $variant){
                    $vid = $this->gf_normalize_font_name($font->family . " " . $this->gf_fancy_font_name($variant));
                    $vchecked = null;
                    $readonly = null;
                    if($is_selection){
                        $vchecked = $this->gf_is_variant_checked($variant, $this->options['googlefont_selections'][$name]['variants']);
                    }
                    if($is_selection && !$has_variants){
                        $readonly = " readonly='readonly'";
                    }
                    $variants .= '<input type="checkbox" id="'.$vid.'" name="'.$name.'[variants][]" value="'.
                                    $variant.'" class="check ' . $normalized_name.'"'. $vchecked . $readonly . '> <label for="'.$vid.
                                    '">' . $this->gf_fancy_font_name($variant) . '</label><br>';
                }
                $variants .= "</div>\n";
            }
            return $variants;
        }
        function gf_get_font_selections($name, $variants, $subsets){
            $out = null;
            $out.= "<div class='webfonts-selections'>\n";
                ?>
                <script>
                    jQuery('#googlefont1-select').change(function(event) {
                        var font = jQuery('#googlefont1-select').val().replace("-", " ");
                        var fonthead = "<link media='all' type='text/css' href='//fonts.googleapis.com/css?family=" + jQuery('#googlefont1-select').val().replace("-", "+") + "' id='googlefonts-css google1' rel='stylesheet'> ";
                        var templateDir = "<?php  admin_url( 'options-general.php?page=google-fonts.php', '' ) ?>";
                        var gf_aj_name = jQuery(this).parent().attr("id");
                        jQuery.post(templateDir,function(data,status){
                            jQuery('#googlefont1preview').css( "font-family", font );
                            jQuery('head #google1').remove();
                            jQuery('head').append(fonthead);
                            }
                        );
                    });
                    jQuery('#googlefont2-select').change(function(event) {
                        var font = jQuery('#googlefont2-select').val().replace("-", " ");
                        var fonthead = "<link media='all' type='text/css' href='//fonts.googleapis.com/css?family=" + jQuery('#googlefont2-select').val().replace("-", "+") + "' id='googlefonts-css google2' rel='stylesheet'> ";
                        var templateDir = "<?php  admin_url( 'options-general.php?page=google-fonts.php', '' ) ?>";
                        jQuery.post(templateDir,function(data,status){
                            jQuery('#googlefont2preview').css( "font-family", font );
                            jQuery('head #google2').remove();
                            jQuery('head').append(fonthead);
                            }
                        );
                    });
            </script>
                <?php
                    $normal_name = $this->options['googlefont_selections'][$name]['family'];
                    if($normal_name){
                        $out.= "<div class='webfonts-preview'><h3>".esc_html__('Preview:', 'blackfyre')."</h3>\n";
                        $out.= "<div id='".$name."preview' style='font-size: 30px; line-height: 1; font-family: ".str_replace("-", " ", $normal_name).";' >Grumpy wizards make toxic brew for the evil Queen and Jack.</div>";
                        $out.= "</div>\n";
                    }
                /* add in all variants that will appear through jQuery */
                $out.= "<div class='webfonts-variants'><h3>" . esc_html__('1. Choose the font styles you want:*', 'blackfyre') . "</h3>\n".$variants."</div>\n";
                /* add in the dom elements the user would like it to affect and custom css box */
                $out.= "<div class='webfonts-usage'><h3>" . esc_html__('2. Elements you want to assign this font to:*', 'blackfyre') . "</h3>\n".$this->gf_get_usage_checkboxes($name)."</div>\n";
                $out.= "<div class='webfonts-css'><h3>" . esc_html__('3. Custom CSS (optional):', 'blackfyre') . "</h3>\n<textarea name='".$name."[css]' id='".$name."_css'>".stripslashes($this->options[$name."_css"])."</textarea>\n</div>\n";
                /* add in subsets */
                $out.= "<div class='webfonts-subsets'><h3>" . esc_html__('4. Choose character sets you want.', 'blackfyre') . "</h3>\n".$subsets."</div>\n";
            $out.="</div>";
            return $out;
        }
        function gf_get_font_data_by_family($googlefont, $family, $data_type){
            $data = null;
            if(is_string($family)){
                $font = null;
                if($this->gf_fonts){
                    if(!is_array($this->gf_fonts)){
                        $fonts = json_decode($this->gf_fonts);
                    }else{
                        $fonts = $this->gf_fonts;
                    }
                    foreach($fonts->items as $findfont){
                        if($this->gf_normalize_font_name($findfont->family) == $family){
                            $font = $findfont;
                        }
                    }
                }
                if($font && is_object($font)){
                    if($data_type == 'variants'){
                        $data = $this->gf_get_font_variants($googlefont, $font);
                    }
                    if($data_type == 'subsets'){
                        $data = $this->gf_get_font_subsets($googlefont, $font);
                    }
                }
            }
            return $data;
        }
        function gf_is_selected($item, $compare){
            if(is_string($item)){$item = strtolower($item);}
            if(is_string($compare)){$compare = strtolower($compare);}
            if($item==$compare){
                return (' selected=selected');
            }
            return null;
        }
        function gf_is_checked($item, $compare){
            if(is_string($item)){$item = strtolower($item);}
            if(is_string($compare)){$compare = strtolower($compare);}
            if(is_array($compare) && $compare){
                if(in_array($item, $compare)){
                    return (' checked=checked');
                }
            }elseif($item==$compare){
                return (' checked=checked');
            }
            return null;
        }
        function gf_is_variant_checked($item, $compare){
            $checked = ' checked=checked';
            if(is_string($item)){$item = strtolower($item);}
            if(is_string($compare)){$compare = strtolower($compare);}
            if(is_array($compare) && $compare){
                if(in_array($item, $compare)){
                    return $checked;
                }
            }elseif($item == $compare){
                return $checked;
            }
            return null;
        }
        function gf_normalize_font_name($name){
            return(str_replace(" ","-",trim($name)));
        }
        function gf_fancy_font_name($name){
            $ids = $this->font_styles_translation;
            $text = array();
            foreach($ids as $key=>$val){
                $pos = stripos((string)$name,(string)$key);
                if($pos !== false){
                    if($key == 'regular'){
                        $key = null;
                    }
                    $text[]= "$val $key";
                }
            }
            if(stripos($name,'italic') !== false){
                $text[]='Italic';
            }
            if($name == 'italic'){
                $text=array('Normal 400 Italic');
            }
            $name = implode(' ',$text);
            return $name;
        }
        function gf_get_usage_checkboxes($element){
            $out = null;
            if(is_array($this->gf_usage_elements)){
                /* get current selections */
                foreach($this->gf_usage_elements as $key => $val){
                    $checked = null;
					if(!isset($this->options[$element."_".$key]))$this->options[$element."_".$key]='';
                    if($this->options[$element."_".$key] == "checked"){
                        $checked = ' checked="checked"';
                    }
                    $out .= '<input type="checkbox" id="' . $element . "_" . $key . '" name="'.
                            $element.'[usage][]" value="'.$key.'"'.$checked.'> <label for="' .
                            $element . "_" . $key . '">'.$val.'</label><br>';
                }
            }
            return $out;
        }
        /* replaces listgooglefontoptions functionality from prior to v3.0 */
        function gf_get_selection_boxes($element_names){
            $this->gf_get_font_file();
            $out = null;
            if(is_array($element_names)){
                $i = 1;
                foreach($element_names as $name){
                    $out.="<h2>" . sprintf(esc_html__('Font %s', 'blackfyre'), $i) . "</h2>\n";
                    $out.= $this->gf_get_fonts_select($name);
                    $i++;
                }
            }
            return ($out);
        }
        /* deprecated and replaced by gf_get_selection_boxes in v3.0 */
        function listgooglefontoptions(){
            return null;
        }
        function get_api_query(){
            $query = null;
            $fonts = $this->options['googlefont_selections'];
            $families = array();
            $subsets = array();
            if($fonts && is_array($fonts)){
                $i = 0;
                foreach($fonts as $fontvars){
                    if(isset($fontvars['family']) && $fontvars['family']){
                        /* Proper Case everything, otherwise Google does not recognize it */
                        $words = explode("-",$fontvars['family']);
                        foreach($words as $key => $word){
                            $words[$key] = ucwords($word);
                        }
                        $families[$i] = implode('+', $words);
                        if(isset($fontvars['variants']) && !empty($fontvars['variants'])){
                            /* Convert 'regular' and 'italic' to be the same way Google does it.
                             * It works without converting it, but we do it for the sake of consistency */
                            foreach($fontvars['variants'] as $key => $var){
                                if($var == 'regular'){$fontvars['variants'][$key] = '400';}
                                if($var == 'italic'){$fontvars['variants'][$key] = '400italic';}
                            }
                            $families[$i] = $families[$i] . ":" . implode(",", $fontvars['variants']);
                        }
                        if(isset($fontvars['subsets']) && !empty($fontvars['subsets'])){
                            foreach($fontvars['subsets'] as $sub){
                                if(!in_array($sub, $subsets)){
                                    $subsets[] = $sub;
                                }
                            }
                        }
                    }
                    $i++;
                }
                $query .= "?family=" . implode("|", $families);
                if($subsets){
                    $query .= "&subset=" . implode(",", $subsets);
                }
            }
            return $query;
        }
        /* totally re-written for v3.0 */
        /*work out issue with url being encoded. see script_loader_src hook
        has to do with $tag .= apply_filters( 'style_loader_tag', "... in wp-includes/class.wp-styles.php
        and style_loader_src
        */
        function googlefontsstart(){
            $have_selections = false;
            $options = get_option('googlefonts_options');
            if(isset($options['googlefont_selections'])){
                $fonts = $options['googlefont_selections'];
            }
            if(!empty($fonts)){
                foreach($fonts as $val){
                    if(isset($val['family']) && $val['family'] && $val['family'] != 'off'){
                        $have_selections = true;
                    }
                }
            }
            if($have_selections){
                // check to see if site is uses https
                $http = (!empty($_SERVER['HTTPS'])) ? "https" : "http";
                $url = $http.'://fonts.googleapis.com/css';
                $url .= $this->get_api_query();
                //possibly add a checkbox to admin to add this code to the head manually if enqueue does not work
                add_filter('style_loader_tag',array(&$this,'gf_url_filter'),1000,2);
                wp_enqueue_style('googlefonts',$url, null, null);
                remove_filter('style_loader_tag',array(&$this,'gf_url_filter'));
            }
        }
        function gf_url_filter($tag=null, $handle=null){
            if($handle == 'googlefonts'){
                //put back in regular ampersands //
                $tag = str_replace('#038;', '', $tag);
            }
            return $tag;
        }
        /* maybe replace with a custom style sheet in the future */
        function addgooglefontscss() {
            $uses = $this->gf_usage_elements_map;
            $names = $this->gf_element_names;
            $styles = null;
            /* do for all in gf_element_names */
            if($uses && $names){
                foreach($names as $font => $name){
                    $family = null;
                    if(isset($this->options['googlefont_selections'][$name]['family'])){
                        $family = $this->options['googlefont_selections'][$name]['family'];
                    }
                    if($family){
                        foreach($uses as $key => $tag){
                            if($this->options[$name . "_" . $key] == 'checked'){
                                $styles .= "\t" . $tag . '{ font-family:"'.str_replace("-", " ", $family).'", arial, sans-serif;}';
                                $styles .= "\n";
                            }
                        }
                        if(trim($this->options[$name . '_css'])){
                            $styles .= "\t" . trim(stripslashes($this->options[$name . '_css'])). "\n";
                        }
                    }
                }
            }
            if($styles){
                echo "<style type='text/css' media='screen'>\n";
                echo esc_attr($styles);
                echo "</style>\n<!-- fonts delivered by Wordpress Google Fonts, a plugin by Adrian3.com -->";
            }
        }
        /**
        * Retrieves the plugin options from the database.
        * @return array
        */
        function getOptions() {
            //Don't forget to set up the default options
            if (!$theOptions = get_option($this->optionsName)) {
                            $theOptions = array(
                /* leave for backwards compatability */
                'googlefonts_font1'=>'',
                'googlefonts_font2'=>'',
                /* end of leave for backwards compatability */
                'googlefont1_css'=>' ',
                'googlefont1_heading1'=>'unchecked',
                'googlefont1_heading2'=>'unchecked',
                'googlefont1_heading3'=>'unchecked',
                'googlefont1_heading4'=>'unchecked',
                'googlefont1_heading5'=>'unchecked',
                'googlefont1_heading6'=>'unchecked',
                'googlefont1_body'=>'unchecked',
                'googlefont1_blockquote'=>'unchecked',
                'googlefont1_p'=>'unchecked',
                'googlefont1_li'=>'unchecked',
                'googlefont2_css'=>' ',
                'googlefont2_heading1'=>'unchecked',
                'googlefont2_heading2'=>'unchecked',
                'googlefont2_heading3'=>'unchecked',
                'googlefont2_heading4'=>'unchecked',
                'googlefont2_heading5'=>'unchecked',
                'googlefont2_heading6'=>'unchecked',
                'googlefont2_body'=>'unchecked',
                'googlefont2_blockquote'=>'unchecked',
                'googlefont2_p'=>'unchecked',
                'googlefont2_li'=>'unchecked',
                /* primary fonts variable as of v3.0 */
                'googlefont_data_time' => 0,
                'googlefont_selections' => array(
                        'googlefont1' => array(
                            'family' => null,
                            'variants' => array(),
                            'subsets' => array()
                        )
                    ),
                'googlefont_data_converted' => false
            );
                update_option($this->optionsName, $theOptions);
            }
            $this->options = $theOptions;
            if(!isset($this->options['googlefont_data_time'])){
                $this->options['googlefont_data_time'] = 0;
                $this->saveAdminOptions();
            }
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //There is no return here, because you should use the $this->options variable!!!
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }
        /* convert fonts in old options variable to new array variable */
        function gf_convert_fonts(){
            $converted = false;
            if(isset($this->options['googlefont_data_converted'])){
                $converted = $this->options['googlefont_data_converted'];
            }
            if(!$converted){
                foreach($this->gf_element_names as $option => $name){
                    $family = null;
                    $variants = array();
                    if(isset($this->options[$option]) && $this->options[$option]){
                        if($this->options[$option] == 'off'){
                            /* set to empty array for this font */
                            $this->options['googlefont_selections'][$name] = array(
                                'family' => null,
                                'variants' => array(),
                                'subsets' => array()
                            );
                        }else{
                            /* convert current string to array */
                            /* get the font family, everything left of the ':' */
                            $font = $this->options[$option];
                            $delim = stripos($font,":");
                            if($delim  !== false){
                                $family = $this->gf_normalize_font_name(substr($font, 0, $delim));
                                $variations = substr($font, $delim + 1);
                                $variants = explode(",",$variations);
                            }else{
                                $family = $this->gf_normalize_font_name($font);
                                $variants = array('regular');
                            }
                            /* standardize all '400' to 'regular', and '400italic' to 'italic',
                             * and 'bold' to 700 and bolditalic to 700italic, and 'light' to 300
                             * to match Google's naming convention */
                            if($variants && is_array($variants)){
                                foreach($variants as $key => $val){
                                    if($val == '400' || $val == 400){$variants[$key] = 'regular';}
                                    if($val == '400italic'){$variants[$key] = 'italic';}
                                    if(strtolower($val) == 'bold'){$variants[$key] = '700';}
                                    if(strtolower($val) == 'bolditalic'){$variants[$key] = '700italic';}
                                    if(strtolower($val) == 'light'){$variants[$key] = '300';}
                                    if(strtolower($val) == 'lightitalic'){$variants[$key] = '300italic';}
                                }
                            }
                            /* set the options */
                            $this->options['googlefont_selections'][$name]['family'] = $family;
                            $this->options['googlefont_selections'][$name]['variants'] = $variants;
                            /* leave subsets blank for the form javascript to handle it at run time because not all are latin */
                            $this->options['googlefont_selections'][$name]['subsets'] = array();
                            /* clear old option */
                            $this->options[$option] = '';
                        }
                    }else{
                        /* skip it if it has already been converted */
                        if(!isset($this->options['googlefont_selections'][$name]['family']) || !$this->options['googlefont_selections'][$name]['family']){
                            /*working with the old array or empty array, set new array to empty for this font */
                            $this->options['googlefont_selections'][$name] = array(
                                    'family' => null,
                                    'variants' => array(),
                                    'subsets' => array()
                                );
                            /* clear old option */
                            $this->options[$option] = '';
                        }
                    }
                }
                //note that we've made the conversion from prior to 3.0
                $this->options['googlefont_data_converted'] = true;
                //save the changes
                $this->saveAdminOptions();
            }
        }
        /**
        * Saves the admin options to the database.
        */
        function saveAdminOptions(){
            return update_option($this->optionsName, $this->options);
        }
        /**
        * @desc Adds the options subpanel
        */
        function admin_menu_link() {
            //If you change this from add_options_page, MAKE SURE you change the filter_plugin_actions function (below) to
            //reflect the page filename (ie - options-general.php) of the page your plugin is under!
            add_menu_page('Google Fonts', 'Google Fonts', 'manage_options', basename(__FILE__), array(&$this,'admin_options_page'), '', '1801');
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
        }
        /**
        * @desc Adds the Settings link to the plugin activate/deactivate page
        */
        function filter_plugin_actions($links, $file) {
           //If your plugin is under a different top-level menu than Settiongs (IE - you changed the function above to something other than add_options_page)
           //Then you're going to want to change options-general.php below to the name of your top-level page
           $settings_link = '<a href="admin.php?page=' . basename(__FILE__) . '">' . esc_html__('Settings', 'blackfyre') . '</a>';
           array_unshift( $links, $settings_link ); // before other links
           return $links;
        }
        function gf_handle_submission($data){
            if (! wp_verify_nonce($_POST['_wpnonce'], 'googlefonts-update-options') ) die(esc_html__('Whoops! There was a problem with the data you posted. Please go back and try again.', 'blackfyre'));
            if(is_array($data)){
                foreach($data as $googlefont => $options){
                    if(is_array($options) && in_array($googlefont, $this->gf_element_names)){
                        /* store the family, variants, css and usage options */
                        foreach($options as $option => $value){
                            if($option=='family' || $option=='variants' || $option=='subsets'){
                                $this->options['googlefont_selections'][$googlefont][$option] = $value;
                            }
                            if($option=='css'){
                                $this->options[$googlefont . '_' . $option] = $value;
                            }
                        }
                        //have to check and set all usage options separately because they are not an array
                        if(isset($options['usage']) && is_array($options['usage']) && $options['usage']){
                            foreach($this->gf_usage_elements as $key => $val){
                                if(in_array($key, $options['usage'])){
                                    $this->options[$googlefont . "_" . $key] = 'checked';
                                }else{
                                    $this->options[$googlefont . "_" . $key] = 'unchecked';
                                }
                            }
                        }else{
                            foreach($this->gf_usage_elements as $key => $val){
                                $this->options[$googlefont . "_" . $key] = 'unchecked';
                            }
                        }
                    }
                }
                return ($this->saveAdminOptions());
            }
            return false;
        }
        /**
        * Adds settings/options page
        */
        function admin_options_page() {
            $message = null;
            if(isset($_POST['googlefonts_save']) && $_POST['googlefonts_save']){
                if($this->gf_handle_submission($_POST)){
                    $message = '<div class="updated"><p>' . esc_html__('Success! Your changes were sucessfully saved!', 'blackfyre') . '</p></div>';
                }else{
                    $message = '<div class="error"><p>' . esc_html__('Error. Either you did not make any changes, or your changes did not save. Please try again.', 'blackfyre') . '</p></div>';
                }
            }
            ?>
            <div class="wrap">
                <table width="650" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                        <h1><?php esc_html_e('Google Font Control Panel', 'blackfyre'); ?></h1>
                        <hr />
                        <form method="post" id="googlefonts_options">
                        <?php wp_nonce_field('googlefonts-update-options'); ?>
                        <h2><?php esc_html_e('Select Fonts', 'blackfyre');?></h2>
                        <?php if($message){echo  $message;} ?>
                        <?php
                            /* This call gets all the font boxes and also sets some of the class options.
                             * gf_print_notices must be called after gf_get_selection boxes, or the notices
                             * will be empty.
                            */
                            echo  $this->gf_get_selection_boxes($this->gf_element_names);
                        ?>
                        </form>
                    </td>
                  </tr>
                </table>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery('.webfonts-select').change(function() {
                            var gf_aj_name = jQuery(this).parent().attr("id");
                            var gf_aj_family = jQuery(this).val();
                            var data = {
                                action: 'googlefont_action',
                                security: '<?php wp_create_nonce("gf-string");?>',
                                googlefont_ajax_family: gf_aj_family,
                                googlefont_ajax_name: gf_aj_name
                            };
                            if(gf_aj_family == '' || gf_aj_family == 'off'){
                                gf_reset_selections(gf_aj_name, gf_aj_family);
                                return false;
                            }else{
                                jQuery('#' + gf_aj_name + ' .webfonts-waiting').html('<div class="gfspinner"></div>');
                                jQuery.post(ajaxurl, data, function(response) {
                                    jQuery('#' + gf_aj_name + ' .webfonts-waiting').html('');
                                    jQuery('#' + gf_aj_name + ' .webfonts-selections').replaceWith(response);
                                    gf_reset_selections(gf_aj_name, gf_aj_family);
                                    show_selected_items(jQuery('#' + gf_aj_name));
                                });
                            }
                        });
                        jQuery('.show_hide').click(function() {
                            var parent = jQuery(this).parent();
                            if (jQuery(this).hasClass("showing")){
                                hide_selected_items(parent);
                            }else{
                                show_selected_items(parent);
                            }
                            return false;
                        });
                        /* update the selections on change */
                        function gf_reset_selections(fontblock, selected_font) {
                            var name = get_normalized_name(selected_font);
                            var parent = jQuery('#' + fontblock);
                            var parentid = jQuery(parent).attr("id");
                            if(selected_font != '' && selected_font != 'off'){
                                /* pre select variant and character set */
                                pre_select_items(parent, name);
                            }else{
                                /* clear all the items and hide them */
                                jQuery('#' + parentid + ' .webfonts-variants :checked').attr('checked', false);
                                jQuery('#' + parentid + ' .webfonts-usage :checked').attr('checked', false);
                                jQuery('#' + parentid + ' .webfonts-subsets :checked').attr('checked', false);
                                jQuery('#' + parentid + '_css').val(' ');
                                hide_selected_items(parent);
                            }
                        };
                        function show_selected_items(parent){
                            /* limit all our actions to just within this specific selection */
                            var parentid = jQuery(parent).attr("id");
                            jQuery('#show_hide-' + parentid ).addClass('showing');
                            jQuery('#show_hide-' + parentid ).html('Hide Options');
                            jQuery('#' + parentid + ' .webfonts-selections').fadeIn(500);
                        }
                        function hide_selected_items(parent){
                            /* limit all our actions to just within this specific selection */
                            var parentid = jQuery(parent).attr("id");
                            jQuery('#show_hide-' + parentid ).removeClass('showing');
                            jQuery('#show_hide-' + parentid ).html('Show Options');
                            jQuery('#' + parentid + ' .webfonts-selections').fadeOut(500);
                        }
                        function get_normalized_name(name){
                            return (name.replace(" ","-"));
                        }
                        function pre_select_items(parent, normalized){
                            var parentid = jQuery(parent).attr('id');
                            /* select 'regular' variant if available, or only variant, or first one */
                            var variants = jQuery('#' + parentid + ' .variant-' + normalized + ' .check');
                            var regular = jQuery('#' + parentid + ' .variant-' + normalized + ' [value="regular"]');
                            if(variants.size() > 1){
                                if(regular.size()==1){
                                    regular.attr('checked',true);
                                }else{
                                    variants.first().attr('checked',true)
                                }
                            }
                            if(variants.size()==1){
                                variants.attr('checked',true);
                                variants.attr('readonly','readonly');
                            }
                            /* select latin subset if available, or only subset, or first one */
                            var subsets = jQuery('#' + parentid + ' .subset-' + normalized + ' .check');
                            var latin = jQuery('#' + parentid + ' .subset-' + normalized + ' [value="latin"]');
                            if(subsets.size() > 1){
                                if(latin.size()==1){
                                    latin.attr('checked',true);
                                }else{
                                    subsets.first().attr('checked',true)
                                }
                            }
                            if(subsets.size()==1){
                                subsets.attr('checked',true);
                                subsets.attr('readonly','readonly');
                            }
                        }
                    });
                </script>
        <?php
        } //end admin_options_page
        // ajax handling
        function googlefont_action_callback() {
            $name = $_POST['googlefont_ajax_name'];
            $family = $_POST['googlefont_ajax_family'];
            $normalized_name = $this->gf_normalize_font_name($family);
            $variants = $this->gf_get_font_data_by_family($name, $family, 'variants');
            $subsets = $this->gf_get_font_data_by_family($name, $family, 'subsets');
            if(!$variants){
                $variants = '<input type="checkbox" name="'.$name.'[variants][]" value="regular" class="check '.$normalized_name.' blank"> <label>Normal 400</label>';
            }
            if(!$subsets){
                $subsets = '<input type="checkbox" name="'.$name.'[subsets][]" value="latin" class="check '.$normalized_name.' blank"> <label>Latin</label>';
            }
            echo  $this->gf_get_font_selections($name, $variants, $subsets);
            die();
        }
    } //End Class
} //End if class exists statement
//instantiate the class
if (class_exists('googlefonts')) {
    $googlefonts_var = new googlefonts();
}
?>