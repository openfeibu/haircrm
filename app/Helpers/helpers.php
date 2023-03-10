<?php

use Illuminate\Support\Facades\Request;
use App\Facades\Hashids;
use App\Facades\Trans;
use Xigen\Library\OnBuy\Auth as OnBuyAuth;

if (!function_exists('hashids_encode')) {
    /**
     * Encode the given id.
     *
     * @param int/array $id
     *
     * @return string
     */
    function hashids_encode($idorarray)
    {
        return Hashids::encode($idorarray);
    }

}

if (!function_exists('hashids_decode')) {
    /**
     * Decode the given value.
     *
     * @param string $value
     *
     * @return array / int
     */
    function hashids_decode($value)
    {
        $return = Hashids::decode($value);

        if (empty($return)) {
            return null;
        }

        if (count($return) == 1) {
            return $return[0];
        }

        return $return;
    }

}

if (!function_exists('folder_new')) {
    /**
     * Get new upload folder pathes.
     *
     * @param string $prefix
     * @param string $sufix
     *
     * @return array
     */
    function folder_new($prefix = null, $sufix = null)
    {
        $folder        = date('Y/m/d/His') . rand(100, 999);
        return $folder;
    }
}

if (!function_exists('blade_compile')) {
    /**
     * Get new upload folder pathes.
     *
     * @param string $prefix
     * @param string $sufix
     *
     * @return array
     */
    function blade_compile($string, array $args = [])
    {
        $compiled = \Blade::compileString($string);
        ob_start() and extract($args, EXTR_SKIP);

        // We'll include the view contents for parsing within a catcher

        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try
        {
            eval('?>' . $compiled);
        }

            // If we caught an exception, we'll silently flush the output

            // buffer so that no partially rendered views get thrown out
            // to the client and confuse the user with junk.
        catch (\Exception $e) {
            ob_get_clean();throw $e;
        }

        $content = ob_get_clean();
        $content = str_replace(['@param  ', '@return  ', '@var  ', '@throws  '], ['@param ', '@return ', '@var ', '@throws '], $content);

        return $content;

    }

}


if (!function_exists('trans_url')) {
    /**
     * Get translated url.
     *
     * @param string $url
     *
     * @return string
     */
    function trans_url($url)
    {
        return Trans::to($url);
    }

}

if (!function_exists('trans_dir')) {
    /**
     * Return the direction of current language.
     *
     * @return string (ltr|rtl)
     *
     */
    function trans_dir()
    {
        return Trans::getCurrentTransDirection();
    }

}

if (!function_exists('trans_setlocale')) {
    /**
     * Set local for the translation
     *
     * @param string $locale
     *
     * @return string
     */
    function trans_setlocale($locale = null)
    {
        return Trans::setLocale($locale);
    }

}

if (!function_exists('checkbox_array')) {
    /**
     * Convert array to use in form check box
     *
     * @param array $array
     * @param string $name
     * @param array $options
     *
     * @return array
     */
    function checkbox_array(array $array, $name, $options = [])
    {
        $return = [];

        foreach ($array as $key => $val) {
            $return[$val] = array_merge(['name' => "{$name}[{$key}]"], $options);
        }

        return $return;
    }

}

if (!function_exists('pager_array')) {
    /**
     * Return request values to be used in paginator
     *
     * @return array
     */
    function pager_array()
    {

        return Request::only(
            config('database.criteria.params.search', 'search'),
            config('database.criteria.params.searchFields', 'searchFields'),
            config('database.criteria.params.columns', 'columns'),
            config('database.criteria.params.sortBy', 'sortBy'),
            config('database.criteria.params.orderBy', 'orderBy'),
            config('database.criteria.params.with', 'with')
        );
    }

}

if (!function_exists('user_type')) {
    /**
     * Get user id.
     *
     * @param string $guard
     *
     * @return int
     */
    function user_type($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        $provider = config("auth.guards." . $guard . ".provider", 'users');
        return config("auth.providers.$provider.model", App\User::class);
    }

}

if (!function_exists('user_id')) {
    /**
     * Get user id.
     *
     * @param string $guard
     *
     * @return int
     */
    function user_id($guard = null)
    {

        $guard = is_null($guard) ? getenv('guard') : $guard;

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user()->id;
        }
        return null;
    }

}

if (!function_exists('get_guard')) {
    /**
     * Return thr property of the guard for current request.
     *
     * @param string $property
     *
     * @return mixed
     */
    function get_guard($property = 'guard')
    {
        switch ($property) {
            case 'url':
                return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
                break;
            case 'route':
                return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
                break;
            case 'model':
                $provider = config("auth.guards." . getenv('guard') . ".provider", 'users');
                return config("auth.providers.$provider.model", App\User::class);
                break;
            default:
                return getenv('guard');
        }
    }

}

if (!function_exists('guard_url')) {
    /**
     * Return thr property of the guard for current request.
     *
     * @param string $property
     *
     * @return mixed
     */
    function guard_url($url, $translate = true)
    {
        $prefix = empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
        if ($translate){
            return trans_url($prefix . '/' . $url);
        }
        return $prefix . '/' . $url;
    }

}

if (!function_exists('set_route_guard')) {
    /**
     * Set local for the translation
     *
     * @param string $locale
     *
     * @return string
     */
    function set_route_guard($sub = 'web', $guard=null,$theme=null)
    {
        $i = ($sub == 'web') ? 1 : 2;
        $theme ? set_theme($theme) : '';
        //check whether guard is the first parameter of the route
        $guard = is_null($guard) ? request()->segment($i) : $guard;
        if (!empty(config("auth.guards.$guard"))){
            putenv("guard={$guard}.{$sub}");
            app('auth')->shouldUse("{$guard}.{$sub}");
            return $guard;
        }

        //check whether guard is the second parameter of the route
        $guard = is_null($guard) ? request()->segment(++$i) : $guard;
        if (!empty(config("auth.guards.$guard"))){
            putenv("guard={$guard}.{$sub}");
            app('auth')->shouldUse("{$guard}.{$sub}");
            return $guard;
        }

        putenv("guard=client.{$sub}");
        app('auth')->shouldUse("client.{$sub}");
        return $sub;
    }

}
if(!function_exists('set_theme'))
{
    function set_theme($theme = '')
    {
        if(!empty($theme))
        {
            putenv("theme={$theme}");
        }
    }
}


if (!function_exists('users')) {
    /**
     * Get upload folder.
     *
     * @param string $folder
     *
     * @return string
     */
    function users($property, $guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user()->$property;
        }
        return null;
    }

}

if (!function_exists('user')) {
    /**
     * Return the user model
     * @param type|null $guard
     * @return type
     */
    function user($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user();
        }

        return null;
    }

}

if (!function_exists('user_check')) {
    /**
     * Check whether user is logged in
     * @param type|null $guard
     * @return type
     */
    function user_check($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        return Auth::guard($guard)->check();
    }

}

if (!function_exists('format_date')) {
    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     *
     * @return date
     */
    function format_date($date, $format = 'd M Y')
    {
        if (empty($date)) return null;
        return date($format, strtotime($date));
    }

}

if (!function_exists('format_date_time')) {
    /**
     * Format datetime
     *
     * @param date $datetime
     * @param string $format
     *
     * @return datetime
     */
    function format_date_time($datetime, $format = 'd M Y h:i A')
    {
        return date($format, strtotime($datetime));
    }

}

if (!function_exists('format_time')) {
    /**
     * Format time.
     *
     * @param string $time
     * @param string $format
     *
     * @return time
     */
    function format_time($time, $format = 'h:i A')
    {
        return date($format, strtotime($time));
    }

}
if (!function_exists('theme_asset')) {
    /**
     * Get translated url.
     *
     * @param string $url
     *
     * @return string
     */
    function theme_asset($file)
    {
        return app('theme')->asset()->url($file);
    }
}
if (!function_exists('replace_image_url')) {
    function replace_image_url($content,$url)
    {
        if($url)
        {
            preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $content, $matches);
            $img = "";
            if(!empty($matches)) {
                $img = $matches[2];
            }
            if(!empty($img))
            {
                $patterns= array();
                $replacements = array();
                foreach($img as $imgItem){
                    if(strpos($imgItem,'http') === false)
                    {
                        $final_imgUrl = $url.$imgItem;
                        $replacements[] = $final_imgUrl;
                        $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
                        $patterns[] = $img_new;
                    }
                }
                ksort($patterns);
                ksort($replacements);
                $vote_content = preg_replace($patterns, $replacements, $content);
                return $vote_content;
            } else {
                return $content;
            }
        } else {
            return $content;
        }
    }
}
if (!function_exists('get_substr')) {
    function get_substr($str, $len = 12, $dot = true)
    {
        $i = 0;
        $l = 0;
        $c = 0;
        $a = array();
        while ($l < $len) {
            $t = substr($str, $i, 1);
            if (ord($t) >= 224) {
                $c = 3;
                $t = substr($str, $i, $c);
                $l += 2;
            } elseif (ord($t) >= 192) {
                $c = 2;
                $t = substr($str, $i, $c);
                $l += 2;
            } else {
                $c = 1;
                $l++;
            }
            $i += $c;
            if ($l > $len) break;
            $a[] = $t;
        }
        $re = implode('', $a);
        if (substr($str, $i, 1) !== false) {
            array_pop($a);
            ($c == 1) and array_pop($a);
            $re = implode('', $a);
            $dot and $re .= '...';
        }
        return $re;
    }
}
if (!function_exists('handle_image_url')) {
    function handle_image_url($image_url = '', $host = '')
    {
        $host = $host ? $host : config('app.image_url') . '/';
        if (!empty($image_url) && strpos($image_url, 'http') === false) {
            $image_url = $host . $image_url;
        }
        return $image_url;
    }
}
if (!function_exists('first_image')) {
    function first_image($content)
    {
        $data['content'] = $content;
        $soContent = $data['content'];
        $soImages = '~<img [^>]* />~';
        preg_match_all($soImages, $soContent, $thePics);
        $allPics = count($thePics[0]);
        preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|PNG))\"?.+>/i', $thePics[0][0], $match);
        $data['ig'] = $thePics[0][0];
        if ($allPics > 0) {
            return $match[1];
        } else {
            return null;
        }
    }
}
if (!function_exists('list_image_url_absolute')) {
    function list_image_url_absolute($list, $size = 'sm')
    {
        foreach ($list as $key => $data) {
            $list[$key]['image'] = image_url_absolute($data['image'], $size);
        }
        return $list;
    }
}
if (!function_exists('image_url_absolute')) {
    function image_url_absolute($image, $size = 'sm')
    {
        return $image ? url("/image/" . $size . $image) : '';
    }
}
if (!function_exists('handle_images')) {
    function handle_images($images, $host = '')
    {
        foreach ($images as $key => $image) {
            $images[$key] = handle_image_url($image, $host);
        }
        return $images;
    }
}
if (!function_exists('setting')) {
    function setting($slug, $value = 'value')
    {
        return \App\Models\Setting::where('slug', $slug)->value($value);
    }
}
if (!function_exists('logo')) {
    function logo()
    {
        $logo =  \App\Models\Setting::where('slug', 'logo')->value('value');
        return url('/image/original/'.$logo);
    }
}
if (!function_exists('page')) {
    function page($slug, $value = 'content')
    {
        return \App\Models\Page::where('slug', $slug)->value($value);
    }
}
if (!function_exists('date_html')) {
    function date_html($date)
    {
        $month = date('M',strtotime($date));
        $day = date('d',strtotime($date));
        $html = '<div class="date"><p>'.$day.'</p><span>'.$month.'</span></div>';
        return $html;
    }
}
/*
* ============================== ???????????? html?????????????????? =========================
* @param (string) $str   ??????????????????
* @param (int)  $lenth  ????????????
* @param (string) $repalce ??????????????????$repalce????????????????????????????????????html?????????????????????
* @param (string) $anchor ?????????????????????????????????????????????????????????????????????????????????
* @return (string) $result ?????????
* @demo  $res = cut_html_str($str, 256, '...'); //??????256???????????????????????????'...'??????
* ===============================================================================
*/
if (!function_exists('cut_html_str')) {
    function cut_html_str($str, $lenth, $replace = '......', $anchor = '<!-- break -->')
    {
        $_lenth = mb_strlen($str, "utf-8"); // ?????????????????????????????????????????????????????????
        if ($_lenth <= $lenth) {
            return $str;    // ?????????????????????????????????????????????????????????
        }
        $strlen_var = strlen($str);     // ????????????????????????UTF8?????????-?????????3????????????????????????????????????
        if (strpos($str, '<') === false) {
            return mb_substr($str, 0, $lenth);  // ????????? html ?????? ???????????????
        }
        if ($e = strpos($str, $anchor)) {
            return mb_substr($str, 0, $e);  // ???????????????????????????
        }
        $html_tag = 0;  // html ????????????
        $result = '';   // ???????????????
        $html_array = array('left' => array(), 'right' => array()); //???????????????????????????????????? html ???????????????=>left,??????=>right
        /*
        * ??????????????????<h3><p><b>a</b></h3>?????????p???????????????????????????array('left'=>array('h3','p','b'), 'right'=>'b','h3');
        * ????????? html ?????????<? <% ???????????????????????????????????????????????????
        */
        for ($i = 0; $i < $strlen_var; ++$i) {
            if (!$lenth) break;  // ?????????????????????
            $current_var = substr($str, $i, 1); // ????????????
            if ($current_var == '<') { // html ????????????
                $html_tag = 1;
                $html_array_str = '';
            } else if ($html_tag == 1) { // ?????? html ????????????
                if ($current_var == '>') {
                    $html_array_str = trim($html_array_str); //???????????????????????? <br / > < img src="" / > ???????????????????????????
                    if (substr($html_array_str, -1) != '/') { //????????????????????????????????? /??????????????????????????????????????????
                        // ??????????????????????????? /????????????????????? right ??????
                        $f = substr($html_array_str, 0, 1);
                        if ($f == '/') {
                            $html_array['right'][] = str_replace('/', '', $html_array_str); // ?????? '/'
                        } else if ($f != '?') { // ???????????????? PHP ???????????????
                            // ????????????????????????????????????????????????????????? html ???????????????<h2 class="a"> <p class="a">
                            if (strpos($html_array_str, ' ') !== false) {
                                // ?????????2??????????????????????????????????????????<h2 class="" id="">
                                $html_array['left'][] = strtolower(current(explode(' ', $html_array_str, 2)));
                            } else {
                                //???????????????????????????????????? html ???????????????<b> <p> ???????????????????????????
                                $html_array['left'][] = strtolower($html_array_str);
                            }
                        }
                    }
                    $html_array_str = ''; // ???????????????
                    $html_tag = 0;
                } else {
                    $html_array_str .= $current_var; //???< >????????????????????????????????????,???????????? html ??????
                }
            } else {
                --$lenth; // ??? html ???????????????
            }
            $ord_var_c = ord($str[$i]);
            switch (true) {
                case (($ord_var_c & 0xE0) == 0xC0): // 2 ??????
                    $result .= substr($str, $i, 2);
                    $i += 1;
                    break;
                case (($ord_var_c & 0xF0) == 0xE0): // 3 ??????
                    $result .= substr($str, $i, 3);
                    $i += 2;
                    break;
                case (($ord_var_c & 0xF8) == 0xF0): // 4 ??????
                    $result .= substr($str, $i, 4);
                    $i += 3;
                    break;
                case (($ord_var_c & 0xFC) == 0xF8): // 5 ??????
                    $result .= substr($str, $i, 5);
                    $i += 4;
                    break;
                case (($ord_var_c & 0xFE) == 0xFC): // 6 ??????
                    $result .= substr($str, $i, 6);
                    $i += 5;
                    break;
                default: // 1 ??????
                    $result .= $current_var;
            }
        }
        if ($html_array['left']) { //???????????? html ????????????????????????
            $html_array['left'] = array_reverse($html_array['left']); //??????left?????????????????????????????? html ?????????????????????
            foreach ($html_array['left'] as $index => $tag) {
                $key = array_search($tag, $html_array['right']); // ?????????????????????????????? right ???
                if ($key !== false) { // ???????????? right ??????????????????
                    unset($html_array['right'][$key]);
                } else { // ???????????????????????????
                    $result .= '</' . $tag . '>';
                }
            }
        }
        return $result . $replace;
    }
}
if (!function_exists('drop_blank')) {
    function drop_blank($str)
    {
        $str = preg_replace("/\t/", "", $str); //?????????????????????????????????????????????????????????????????????????????????
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str);  //??????html????????????
        return trim($str); //???????????????
    }
}
if (!function_exists('build_order_sn')) {
    function build_order_sn()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
if (!function_exists('isVaildImage')) {
    function isVaildImage($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->extension()),config('common.img_type'))){
                $error.= $name."????????????;";
            }
            if($file->getClientSize() > config('common.img_size')){
                $img_size = config('common.img_size')/(1024*1024);
                $error.= $name.'??????'.$img_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('isVaildFile')) {
    function isVaildFile($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->extension()),config('common.file_type'))){
                $error.= $name."????????????;";
            }
            if($file->getClientSize() > config('common.file_size')){
                $file_size = config('common.file_size')/(1024*1024);
                $error.= $name.'??????'.$file_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}

if (!function_exists('isVaildExcel')) {
    function isVaildExcel($file)
    {
        $error = '';
        $name = $file->getClientOriginalName();
        if(!$file->isValid())
        {
            $error.= $name.$file->getErrorMessage().';';
        }
//        if(!in_array( strtolower($file->extension()),config('common.excel_type'))){
//            $error.= $name."???".strtolower($file->extension())."????????????Excel??????;";
//        }
        if($file->getClientSize() > config('common.file_size')){
            $file_size = config('common.file_size')/(1024*1024);
            $error.= $name.'??????'.$file_size.'M';
        }

        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('image_png_size_add')) {
    function image_png_size_add($imgsrc, $imgdst,$max_width=1000,$size=0.9)
    {
        list($width, $height, $type) = getimagesize($imgsrc);
        $ratio = $width > $max_width ? $max_width / $width : 1;
        $new_width = $ratio * $width * $size;
        $new_height = $ratio * $height * $size;

        switch ($type) {
            case 1:
                $giftype = check_gifcartoon($imgsrc);
                if ($giftype) {
                    $image_wp = imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagegif($image_wp, $imgdst, 75);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 75);
                imagedestroy($image_wp);
                break;
            case 3:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagesavealpha($image, true);
                imagealphablending($image_wp, false);
                imagesavealpha($image_wp, true);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagepng($image_wp, $imgdst);
                imagedestroy($image_wp);
                break;
        }

    }
}
if (!function_exists('check_gifcartoon')) {
    function check_gifcartoon($image_file)
    {
        $fp = fopen($image_file, 'rb');
        $image_head = fread($fp, 1024);
        fclose($fp);
        return true;
    }
}
if (!function_exists('translate_on_off')) {
    function translate_on_off($val)
    {
        return $val == 'on' ? 1 : 0;
    }
}
if (!function_exists('guard_prefix')) {
    function guard_prefix()
    {
        return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
    }
}
//?????????????????????
//$a ???????????????
//$b ????????????????????????
if (!function_exists('rate_of_increase')) {
    function rate_of_increase($a,$b)
    {
        $a = (int)$a;
        $b = (int)$b;
        if($a==$b){
            return "0.00%";
        }elseif($b==0&&$a>0){
            return "+100%";
        }elseif($a==0&&$b>0){
            return "-100%";
        }elseif($a>$b){
            $c = round(($a-$b)/$b,2);
            $c = $c*100;
            return '+'.$c."%";
        }elseif($a<$b){
            $c = round(($a-$b)/$b,2);
            $c = $c*100;
            return $c."%";
        }else{
            return "????????????";
        }
    }
}
if (!function_exists('freight_config')) {
    function freight_config()
    {
        $freight_categories = \App\Models\FreightCategory::orderBy('id','asc')->get();
        $arr = [];
        foreach ($freight_categories as $key => $freight_category)
        {
            $freights = \App\Models\Freight::where('freight_category_id',$freight_category->id)->orderBy('id','asc')->get();
            foreach ($freights as $key => $freight)
            {
                $arr[$freight_category->id][$freight->freight_area_code] = $freight->toArray();
            }

        }
        return $arr;
    }
}
if (!function_exists('get_freight')) {
    function get_freight($freight_area_code,$freight_category_id,$weight)
    {
        if($weight <=0 || !$freight_category_id)
        {
            return 0;
        }
        $freight_config = freight_config();
        $first_freight = $freight_config[$freight_category_id][$freight_area_code]['first_freight'];
        $continued_freight =  $freight_config[$freight_category_id][$freight_area_code]['continued_freight'];

        if($weight <= 0.5)
        {
            return $first_freight;
        }
        $continued_weight = $weight - 0.5;
        $continued_weight_count = ceil($continued_weight/0.5);
        $continued_freight = $continued_freight * $continued_weight_count;
        return $first_freight + $continued_freight;
    }
}

if (!function_exists('get_admin_model')) {
    function get_admin_model($admin)
    {
        switch ($admin)
        {
            case $admin instanceof \App\Models\Salesman:
                return 'App\Models\Salesman';
            case $admin instanceof \App\Models\AdminUser:
                return 'App\Models\AdminUser';
        }
    }
}
if (!function_exists('get_admin_role')) {
    function get_admin_role_name($admin)
    {
        switch ($admin)
        {
            case "App\\Models\\Salesman":
                return '?????????';
            case "App\\Models\\AdminUser":
                return '??????';
        }
    }
}
if (!function_exists('get_admin_table_model')) {
    function get_admin_table_model($admin)
    {
        switch ($admin)
        {
            case "App\\Models\\Salesman":
                return \App\Models\Salesman::class;
            case "App\\Models\\AdminUser":
                return \App\Models\AdminUser::class;
        }
    }
}
if (!function_exists('get_admin_detail')) {
    function get_admin_detail($admin_model, $admin_id)
    {
        $role_name = get_admin_role_name($admin_model);
        $admin_table_model = get_admin_table_model($admin_model);
        $admin_name = $admin_table_model::where('id', $admin_id)->value('name');
        $admin_name = isset($admin_name) && $admin_name ? $admin_name : '?????????';
        return '(' . $role_name . ')' . $admin_name;
    }
}
if (!function_exists('get_admin_models')) {
    function get_admin_models()
    {
        return ['App\Models\Salesman','App\Models\AdminUser'];
    }
}
if (!function_exists('checkEmail')) {
    function checkEmail($inAddress)
    {
        return (preg_match("/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/i", $inAddress));
    }
}
/**
 * ??????????????????????????????
 */
if (!function_exists('get_weeks')) {
    function get_weeks($time = '', $format = 'Y-m-d')
    {
        $time = $time != '' ? $time : time();
        //????????????
        $date = [];
        for ($i = 1; $i <= 7; $i++) {
            $date[$i] = date($format, strtotime('+' . $i - 7 . ' days', $time));
        }
        return $date;
    }
}
/**
 * ??????????????????????????????
 * @return array
 */
if (!function_exists('get_month_days')) {
    function get_month_days($year_month='')
    {
        $monthDays = [];
        $firstDay = $year_month ? date($year_month.'-01') : date('Y-m-01');
        $i = 0;
        $lastDay = date('Y-m-d', strtotime("$firstDay +1 month -1 day"));
        while (date('Y-m-d', strtotime("$firstDay +$i days")) <= $lastDay)
        {
            $monthDays[] = date('Y-m-d', strtotime("$firstDay +$i days"));
            $i++;
        }
        return $monthDays;
    }
}
/**
 * ?????????????????????
 * @return array
 */
if (!function_exists('get_months')) {
    function get_months($year='')
    {
        $months = [];
        $year = $year ? $year : date('Y');
        for($i = 1;$i<=12;$i++)
        {
            $months[] = $i < 10 ? $year.'-0'.$i : $year.'-'.$i;
        }
        return $months;
    }
}
if (!function_exists('rate_style')) {
    function rate_style($new, $old)
    {
        return $new >= $old ? 'c1' : 'c2';
    }
}

function getOnbuyToken($seller_id)
{
    $onbuy = App\Models\Onbuy\Onbuy::where('seller_id',$seller_id)->first();
    $config = [
        'consumer_key' => $onbuy['consumer_key'],
        'secret_key' =>  $onbuy['secret_key'],
    ];
    $auth = new OnBuyAuth(
        $config
    );
    return $auth->getToken();
}
function international_freight($weight)
{
    return $weight ? round($weight * 0.065 + 16,2) : 0;
}

function excel_column_out_arr($count)
{
    $arr = [];
    for ($i=1;$i<=$count;$i++)
    {
        $arr[$i] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
    }
    return $arr;
}