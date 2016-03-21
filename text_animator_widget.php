<?php
/*
Plugin Name: Scarlett-Angel Text Animator
Author: Stephen McLaughlin
Version: 1.0
*/
//Will explode text by word and attach class for animation
function wordEffects($input, $second, $milisecond, $iteration, $effects, $class, $style, $tag, $wordOrletter)
{
    //$input = input string
    //$second = number of seconds to be incremented
    //$milisecond = number of miliseconds to be incremented
    //$iteration = number of words to skip before incremented the miliseconds
    //$effects = an array containing classes
    //$class = add class to each word
    //$style = add style to each word
    //set default values for first word
    $output = array();
    if ($wordOrletter == "word") {
        $output = explode(" ", $input);
    } //$wordOrletter == "word"
    else {
        $output = str_split($input, 1);
    }
    $effects_output = array();
    $effects_output = explode(",", $effects);
    $effectsCount   = count($effects_output);
    $delay          = 0;
    $point          = 0;
    $count          = 0;
    //iterate through each word
    echo "<$tag class='$class' style='$style'>";
    foreach ($output as $word) {
        echo "<div style='display:inline-block;'><div style='' class='wow " . $effects_output[rand(1, $effectsCount) - 1] . "' data-wow-delay='$delay" . "." . $point . "s'><span>$word&nbsp;</span></div></div>";
        //store the range of possible increments of the miliseconds into an array
        $milisecond_increments = array();
        $array_pos             = 0;
        $while_mili            = $milisecond;
        if ($milisecond <> 0) {
            while ($while_mili < 100) {
                $milisecond_increments[$array_pos] = $while_mili;
                $while_mili                        = $while_mili + $milisecond;
                $array_pos                         = $array_pos + 1;
            } //$while_mili < 100
        } //$milisecond <> 0
        //count total amount of increments
        $steps = count($milisecond_increments);
        //check if the iteration skip
        if ($count == $iteration) {
            //on first after second has been increased
            if ($point == 0 AND $milisecond <> 0) {
                //increase by the first milisecond increment
                $point = $milisecond_increments[0];
            } //$point == 0 AND $milisecond <> 0
            else {
                //check if it is the last milisecond increment
                if ($point == $milisecond_increments[($steps - 1)]) {
                    $point = 0;
                    $delay = $delay + 1;
                    $count = "0";
                } //$point == $milisecond_increments[($steps - 1)]
                else {
                    // check each increment and when found the current one > increase by 1
                    foreach ($milisecond_increments as $key => $step) {
                        if ($point == $step) {
                            $point = $milisecond_increments[($key + 1)];
                            break;
                        } //$point == $step
                    } //$milisecond_increments as $key => $step
                }
            }
        } //$count == $iteration
        // increase word skip iteration
        else {
            $count = $count + 1;
        }
        unset($milisecond_increments);
        unset($steps);
    } //$output as $word
    echo "</$tag>";
}
class SA_TextAnimator extends WP_Widget
{
    function __construct()
    {
        parent::__construct(false, $name = __('Scarlett Angel Text Animator'));
    }
    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);
        extract($instance, EXTR_SKIP);
        wordEffects($input_text, $input_second, $input_milisecond, $input_iteration, $input_effects, $input_class, $input_style, $input_tag, $input_wordOrletter);
    }
    function update($new_instance, $old_instance)
    {
        $instance                       = array();
        $instance['input_text']         = (!empty($new_instance['input_text'])) ? strip_tags($new_instance['input_text']) : '';
        $instance['input_second']       = (!empty($new_instance['input_second'])) ? strip_tags($new_instance['input_second']) : '';
        $instance['input_milisecond']   = (!empty($new_instance['input_milisecond'])) ? strip_tags($new_instance['input_milisecond']) : '';
        $instance['input_iteration']    = (!empty($new_instance['input_iteration'])) ? strip_tags($new_instance['input_iteration']) : '';
        $instance['input_effects']      = (!empty($new_instance['input_effects'])) ? strip_tags($new_instance['input_effects']) : '';
        $instance['input_class']        = (!empty($new_instance['input_class'])) ? strip_tags($new_instance['input_class']) : '';
        $instance['input_style']        = (!empty($new_instance['input_style'])) ? strip_tags($new_instance['input_style']) : '';
        $instance['input_tag']          = (!empty($new_instance['input_tag'])) ? strip_tags($new_instance['input_tag']) : '';
        $instance['input_wordOrletter'] = (!empty($new_instance['input_wordOrletter'])) ? strip_tags($new_instance['input_wordOrletter']) : '';
        return $instance;
    }
    function form($instance)
    {
        // Check the input text 
        if (isset($instance['input_text'])) {
            $input_text = $instance['input_text'];
        } //isset($instance['input_text'])
        else {
            $input_text = __('Write the text you want to display here', 'text_domain');
        }
        // check in input Second
        if (isset($instance['input_second'])) {
            $input_second = $instance['input_second'];
        } //isset($instance['input_second'])
        else {
            $input_second = __('Input a number for Seconds', 'text_domain');
        }
        // check in miliinput Second
        if (isset($instance['input_milisecond'])) {
            $input_milisecond = $instance['input_milisecond'];
        } //isset($instance['input_milisecond'])
        else {
            $input_milisecond = __('Input a number for miliseconds', 'text_domain');
        }
        // check iteration
        if (isset($instance['input_iteration'])) {
            $input_iteration = $instance['input_iteration'];
        } //isset($instance['input_iteration'])
        else {
            $input_iteration = __('Input a number for iterations', 'text_domain');
        }
        // check iteration
        if (isset($instance['input_effects'])) {
            $input_effects = $instance['input_effects'];
        } //isset($instance['input_effects'])
        else {
            $input_effects = __('Write classes seperated by commas \',\'', 'text_domain');
        }
        // check non random class
        if (isset($instance['input_class'])) {
            $input_class = $instance['input_class'];
        } //isset($instance['input_effects'])
        else {
            $input_class = __('Add non randomised class to the surrounding tag', 'text_domain');
        }
        // check style
        if (isset($instance['input_style'])) {
            $input_style = $instance['input_style'];
        } //isset($instance['input_effects'])
        else {
            $input_style = __('add style rules here', 'text_domain');
        }
        // check tag
        if (isset($instance['input_tag'])) {
            $input_tag = $instance['input_tag'];
        } //isset($instance['input_effects'])
        else {
            $input_tag = __('write type of tag here', 'text_domain');
        }
        // check word or Letter
        if (isset($instance['input_wordOrletter'])) {
            $input_wordOrletter = $instance['input_wordOrletter'];
        } //isset($instance['input_effects'])
        else {
            $input_wordOrletter = __('word');
        }
?>
	<p>
		<label for="<?php
        echo $this->get_field_id('input_text');
?>"><?php
        _e('Input Text:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_text');
?>" name="<?php
        echo $this->get_field_name('input_text');
?>" type="text" value="<?php
        echo esc_attr($input_text);
?>">
		
		<label for="<?php
        echo $this->get_field_id('input_second');
?>"><?php
        _e('Animation time incrememnt in seconds:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_second');
?>" name="<?php
        echo $this->get_field_name('input_second');
?>" type="text" value="<?php
        echo esc_attr($input_second);
?>">
		
		<label for="<?php
        echo $this->get_field_id('input_milisecond');
?>"><?php
        _e('Animation time incrememnt in miliseconds:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_milisecond');
?>" name="<?php
        echo $this->get_field_name('input_milisecond');
?>" type="text" value="<?php
        echo esc_attr($input_milisecond);
?>">
		
		<label for="<?php
        echo $this->get_field_id('input_iteration');
?>"><?php
        _e('Number of words to skip before incrementing time:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_iteration');
?>" name="<?php
        echo $this->get_field_name('input_iteration');
?>" type="text" value="<?php
        echo esc_attr($input_iteration);
?>">
		
		<label for="<?php
        echo $this->get_field_id('input_effects');
?>"><?php
        _e('add classes for effects seperated by commas \',\':');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_effects');
?>" name="<?php
        echo $this->get_field_name('input_effects');
?>" type="text" value="<?php
        echo esc_attr($input_effects);
?>">

		<label for="<?php
        echo $this->get_field_id('input_class');
?>"><?php
        _e('write class or classes here seperated by spaces:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_class');
?>" name="<?php
        echo $this->get_field_name('input_class');
?>" type="text" value="<?php
        echo esc_attr($input_class);
?>">

		<label for="<?php
        echo $this->get_field_id('input_style');
?>"><?php
        _e('write inline style rules directly here:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_style');
?>" name="<?php
        echo $this->get_field_name('input_style');
?>" type="text" value="<?php
        echo esc_attr($input_style);
?>">

		<label for="<?php
        echo $this->get_field_id('input_tag');
?>"><?php
        _e('Write the type of surrounding tag eg. h1, h2, p, span:');
?></label>
		<input class="widefat" id="<?php
        echo $this->get_field_id('input_tag');
?>" name="<?php
        echo $this->get_field_name('input_tag');
?>" type="text" value="<?php
        echo esc_attr($input_tag);
?>">

<label for="<?php
        echo $this->get_field_id('input_wordOrletter');
?>"><?php
        _e('Per Word or Per Letter:');
?></label>
		<select id="<?php
        echo $this->get_field_id('input_wordOrletter');
?>" name="<?php
        echo $this->get_field_name('input_wordOrletter');
?>">
<option value="word" <?php
        echo ($input_wordOrletter == 'word' ? 'selected="selected"' : '');
?> >Word</option>
<option value="letter" <?php
        echo ($input_wordOrletter == 'letter' ? 'selected="selected"' : '');
?>>Letter</option>
</select>

	</p>
	<?php
    }
}
add_action('widgets_init', function()
{
    register_widget('SA_TextAnimator');
});
function SA_add_resources()
{
    wp_enqueue_style('animated_css', plugins_url('/css/animate.css', __FILE__));
    wp_enqueue_script('wow_animated', plugins_url('/js/wow.min.js', __FILE__));
    wp_enqueue_script('set_up', plugins_url('/js/setup.js', __FILE__));
}
add_action('wp_enqueue_scripts', 'SA_add_resources');
?>