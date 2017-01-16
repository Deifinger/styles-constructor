<?php

/**
 * Class Style
 */
class Style {

	/**
	 * Using:
	 * new Style(array(
	 *      'selector1' => array(
	 *          'rule1' => 'property',
	 *          'rule2' => 'property'
	 *      ),
	 *      'selector2' => array(
	 *          'rule1' => 'property',
	 *          'rule2' => 'property'
	 *      )
	 * ), true, true);
	 *
	 * or
	 *
	 * $class = new Style();
	 * $class->addRuleWithProperty('.some-class', 'color', 'red')->addProperty('background', 'blue');
	 * $class->add_inline_css('theme-style');
	 */


	private $rules = array();
	private $lastSelector = '';

	/**
	 * @param $rules - array with structure:
	 *
	 * $rules = array(
	 *      'rule1' => array(
	 *          'property1' => 'value',
	 *          'property2' => 'value'
	 *      ),
	 *      'rule2' => array(
	 *          'property1' => 'value',
	 *          'property2' => 'value'
	 *      )
	 * );
	 *
	 * @param bool $is_the_css - is echo constructed css?
	 * @param bool $is_wrapped - is echo constructed css in wrap (<style> tags)?
	 */
	function __construct($rules = array(), $is_the_css = false, $is_wrapped = false) {
		if(!is_array($rules)) return;

		foreach($rules as $rule => $properties) {
			$this->addRule( $rule );
			foreach($properties as $property => $value) {
				$this->addProperty( $property, $value );
			}
		}

		if($is_the_css && $is_wrapped) {
			$this->the_css_wrapped();
			return;
		}

		if($is_the_css) $this->the_css();
	}

	/**
	 * Adds new rule to the $rules array
	 *
	 * @param $ruleSelector
	 *
	 * @return $this|bool
	 */
	public function addRule($ruleSelector) {
		if(empty($ruleSelector)) return false;

		if(!in_array($ruleSelector, $this->rules)) {
			$this->rules[$ruleSelector] = array();
		}
		$this->lastSelector = $ruleSelector;

		return $this;
	}

	/**
	 * Adds new $property and $value to the $rules array
	 *
	 * @param $property
	 * @param $value
	 *
	 * @return $this|bool
	 */
	public function addProperty($property, $value) {
		if(empty($this->lastSelector) || empty($property) || empty($value)) return false;

		$this->rules[$this->lastSelector][$property] = $value;
		return $this;
	}

	/**
	 * Adds new $ruleSelector, $property and $value to the $rules array
	 *
	 * @param $ruleSelector
	 * @param $property
	 * @param $value
	 *
	 * @return $this|bool
	 */
	public function addRuleWithProperty($ruleSelector, $property, $value) {
		if(!$this->addRule($ruleSelector)) return false;
		if(!$this->addProperty($property, $value)) return false;
		return $this;
	}

	/**
	 * Returns constructed css
	 *
	 * @return string
	 */
	public function get_css() {
		$style = '';

		if(empty($this->rules)) return $style;

		foreach($this->rules as $rule => $properties) {
			$style .= $rule.'{';
			if(!empty($properties)) {
				foreach($properties as $property => $value) {
					$style .= $property.':'.$value.';';
				}
			}
			$style .= '}';
		}
		return $style;
	}

	/**
	 * Returns constructed css in wrap (<style> tags)
	 *
	 * @param $styleName - name of the <style> tag
	 * @return string
	 */
	public function get_css_wrapped($styleName) {
		return '<style name="'.$styleName.'" type="text/css">'.$this->get_css().'</style>';
	}

	/**
	 * Echoes constructed css
	 */
	public function the_css() {
		echo $this->get_css();
	}

	/**
	 * Echoes constructed css in wrap (<style> tags)
	 *
	 * @param $styleName - name of the <style> tag
	 */
	public function the_css_wrapped($styleName) {
		echo $this->get_css_wrapped($styleName);
	}

	/**
	 * Uses Wordpress function
	 * @param $handle - Name of the script to which to add the extra styles
	 */
	/*public function add_inline_css( $handle ) {
		wp_add_inline_style( $handle, $this->get_css() );
	}*/
}