<?php
/*
 Plugin Name: Scarlett-Angel Text Animator
 Author: Stephen McLaughlin
 Version: 1.0
 */
//Will explode text by word and attach class for animation
function wordEffects($input, $second, $milisecond, $iteration, $effects, $class, $style, $tag)
{
	//$input = input string
	//$second = number of seconds to be incremented
	//$milisecond = number of miliseconds to be incremented
	//$iteration = number of words to skip before incremented the miliseconds
	//$effects = an array containing classes
	//$class = add class to each word
	//$style = add style to each word
	
	//set default values for first word
    $output       = array();
    $output       = explode(" ", $input);
    $effectsCount = count($effects);
    $delay        = 0;
    $point        = 0;
    $count        = 0;
	//iterate through each word
    foreach ($output as $word) {
        echo "<$tag>";
        echo "<div style='display:inline-block;'><div style='' class='wow " . $effects[rand(1, $effectsCount) - 1] . "' data-wow-delay='$delay" . "." . $point . "s'><span class='$class' style='$style'>$word &nbsp;</span></div></div>";
		//store the range of possible increments of the miliseconds into an array
		$milisecond_increments = array();
        $array_pos             = 0;
        $while_mili            = $milisecond;
        if ($milisecond <> 0) {
            while ($while_mili < 100) {
                $milisecond_increments[$array_pos] = $while_mili;
                $while_mili                        = $while_mili + $milisecond;
                $array_pos                         = $array_pos + 1;
            }
        }
		//count total amount of increments
        $steps = count($milisecond_increments);
		//check if the iteration skip
        if ($count == $iteration) {
			//on first after second has been increased
            if ($point == 0 AND $milisecond <> 0) {
				//increase by the first milisecond increment
                $point = $milisecond_increments[0];
            } else {
				//check if it is the last milisecond increment
                if ($point == $milisecond_increments[($steps - 1)]) {
                    $point = 0;
                    $delay = $delay + 1;
                    $count = "0";
                } else {
					// check each increment and when found the current one > increase by 1
                    foreach ($milisecond_increments as $key => $step) {
                        if ($point == $step) {
                            $point = $milisecond_increments[($key + 1)];
                            break;
                        }
                    }
                }
            }
        }
		// increase word skip iteration
		else {
            $count = $count + 1;
        }
        unset($milisecond_increments);
        unset($steps);
    }
    echo "</$tag>";
}
 class SA_TextAnimator  extends WP_Widget
 {
    function __construct() {
        parent::__construct(false, $name = __('Scarlett Angel Text Animator'));
        
    }
    function SA_TextAnimator()
    {
        
    }
    function widget($args, $instance)
    {
        extract( $args, EXTR_SKIP);
        $title = ( $instance['title'] ) ? $instance['title'] : 'Scarlett-Angel Text Animator';
        $body  ( $instance['body']) ? $instance['body'] : 'Scarlett-Angel Text Animator is not configured';
        echo $before_widget;
        echo $before_title . $title . $after_title;
        
        
        
    }
    function update($new_instance, $old_instance)
    {
        $instance = array();
		$instance['input_text'] = (!empty($new_instance['input_text'])) ? strip_tags( $new_instance['input_text']) : '';
		$instance['input_second'] = (!empty($new_instance['input_second'])) ? strip_tags( $new_instance['input_second']) : '';
		return $instance;
    }
    function form($instance)
    {
		// Check the input text 
     if ( isset ( $instance  ['input_text' ] )) {
		$input_text = $instance['input_text'];
    }
	else {
		$input_text = __('Write the text you want to display here', 'text_domain');
	}
	// check in input Second
	 if ( isset ( $instance  ['input_second' ] )) {
		$input_second = $instance['input_second'];
    }
	else {
		$input_second = __('Input number of Seconds', 'text_domain');
	}
	?>
	<p>
		<label for="<?php echo $this->get_field_id('input_text'); ?>"><?php _e('Input Text:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('input_text'); ?>" name="<?php echo $this->get_field_name('input_text'); ?>" type="text" value="<?php echo esc_attr($input_text); ?>">
		
		<label for="<?php echo $this->get_field_id('input_second'); ?>"><?php _e('Animation time incrememnt in seconds:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('input_second'); ?>" name="<?php echo $this->get_field_name('input_second'); ?>" type="text" value="<?php echo esc_attr($input_second); ?>">
		
		
	</p>
	<?php
 }
 }
 
add_action('widgets_init', function()
		   {
			register_widget('SA_TextAnimator');
		   })
?>