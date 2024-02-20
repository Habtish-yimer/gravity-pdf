<?php

declare( strict_types=1 );

namespace GFPDF\Helper;

use WP_UnitTestCase;

/**
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * @group   helper
 */
class Test_Helper_Field_Container_Gf25 extends WP_UnitTestCase {
	/**
	 * @var Helper_Field_Container_Gf25
	 */
	protected $class;

	public function set_up() {
		parent::set_up(); // TODO: Change the autogenerated stub

		$this->class = new Helper_Field_Container_Gf25();
	}

	/**
	 * Verify running a big chunk of HTML through QueryPath doesn't break our markup in unexpected days
	 * @dataProvider provider_close
	 */
	public function test_close( $expected ): void {
		ob_start();
		$this->class->generate(new \GF_Field());
		echo $expected;
		$this->class->close();

		$test_html = ob_get_clean();
		$minify_html = $this->minify( $test_html );
		$this->assertSame( "<div class=\"row-separator odd\">$expected</div>", $minify_html );
	}

	public function provider_close() {
		return [
			[ '<div class="row-separator odd"><div id="field-3" class="gfpdf-field gfpdf-textarea  grid grid-4"><div class="inner-container"><div class="label"><strong>Rich Text</strong></div><div class="value"><p>This is my content</p><p><strong>It is now bold </strong> but I might add some <em>italics.</em></p><ul><li>This is a list</li><li>Item 2</li><li>Another item</li></ul><p>But maybe I want an ordered list instead...</p><ol><li>This is my ordered list</li><li>I need another one</li></ol><p>Sometimes I find the need for a good quote:</p><blockquote><p>To be, or not to be. That is the question, my friend.</p></blockquote></div></div></div><div id="field-1" class="gfpdf-field gfpdf-signature  grid grid-4"><div class="inner-container"><div class="label"><strong>Signature</strong></div><div class="value"><img src="/Users/jakejackson/Sites/gravitypdf/wp-content/uploads/gravity_forms/signatures/62e8a8ae896dc1.16979298.png" alt="Signature" width="100" /></div></div></div><div id="field-4" class="gfpdf-field gfpdf-html  grid grid-4"><div class="inner-container" style="width: 100%"><div class="value"><img src="clouds.jpg" align="BOTTOM" /><hr /><a href="http://somegreatsite.com">Link Name</a> is a link to another nifty site<h1>This is a Header</h1><h2>This is a Medium Header</h2>Send me mail at <a href="mailto:support@yourcompany.com">support@yourcompany.com</a>.<p> This is a new paragraph!</p><b>This is a new paragraph!</b><br /><b><i>This is a new sentence without a paragraph break, in bold italics.</i></b><hr /></div></div></div></div>' ],
			[ '<pagebreak />' ],
			[ '<tocpagebreak toc-prehtml="&lt;h1&gt;Contents&lt;/h1&gt;" />' ],
			[ '<div class="row-separator"><div class="grid"><div class="inner-container">First</div></div><div class="grid"><div class="inner-container" style="width: 100%"><tocpagebreak toc-prehtml="&lt;h1&gt;Contents&lt;/h1&gt;" /></div></div></div>' ],
		];
	}

	protected function minify($html) {
		$html = preg_replace(
			[ '/\n/', '/\t/', '/\>\s+\</' ],
			[ '', '', '><' ],
			$html
		);

		return $html;
	}

	public function test_css_grid_insertion() {
		$field = new \GF_Field();
		$field->cssClass = '';
		$field->layoutGridColumnSpan = 6;

		$this->class->generate( $field );

		$this->assertSame(' grid grid-6', $field->cssClass );

		$this->class->generate( $field );
		$this->assertSame(' grid grid-6', $field->cssClass );

		ob_end_clean();
	}
}
