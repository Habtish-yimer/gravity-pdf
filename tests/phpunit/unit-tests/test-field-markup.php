<?php

namespace GFPDF\Tests;

use GF_Field_Consent;
use GF_Field_Repeater;
use GF_Field_Text;
use GF_Field_Textarea;
use GFAPI;
use GFPDF\Helper\Fields\Field_Consent;
use GFPDF\Helper\Fields\Field_Repeater;
use GFPDF\Helper\Fields\Field_Text;
use GFPDF\Helper\Fields\Field_Textarea;
use GFPDF\Helper\Helper_QueryPath;
use GPDFAPI;
use WP_UnitTestCase;

/**
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       5.1
 */

/**
 * @since 5.1
 * @group field-markup
 */
class Test_Field_Markup extends WP_UnitTestCase {
	/**
	 * Verify the HTML Mark-up generated by the Repeater field
	 */
	public function test_repeater_field_markup() {
		$form  = $GLOBALS['GFPDF_Test']->form['repeater-consent-form'];
		$entry = $GLOBALS['GFPDF_Test']->entries['repeater-consent-form'][0];

		$form_id          = GFAPI::add_form( $form );
		$entry['form_id'] = $form_id;
		$entry_id         = GFAPI::add_entry( $entry );

		$repeater = new GF_Field_Repeater( $form['fields'][1] );

		$field = new Field_Repeater( $repeater, GFAPI::get_entry( $entry_id ), GPDFAPI::get_form_class(), GPDFAPI::get_misc_class() );

		$qp   = new Helper_QueryPath();
		$html = $qp->html5( $field->html() );

		$this->assertSame( 2, $html->find( '.gfpdf-repeater' )->count() );
		$this->assertSame( 4, $html->find( '.repeater-container' )->count() );
		$this->assertSame( 19, $html->find( '.gfpdf-field' )->count() );

		$this->assertEquals( 'Simon Wiseman', $html->find( '.gfpdf-name .value' )->get( 0 )->nodeValue );
		$this->assertEquals( 'Geoff Simpson', $html->find( '.gfpdf-name .value' )->get( 1 )->nodeValue );
	}

	public function test_consent_field() {
		$form  = $GLOBALS['GFPDF_Test']->form['repeater-consent-form'];
		$entry = $GLOBALS['GFPDF_Test']->entries['repeater-consent-form'][0];

		$form_id          = GFAPI::add_form( $form );
		$entry['form_id'] = $form_id;
		$entry_id         = GFAPI::add_entry( $entry );

		$consent = new GF_Field_Consent( $form['fields'][0] );

		$field = new Field_Consent( $consent, GFAPI::get_entry( $entry_id ), GPDFAPI::get_form_class(), GPDFAPI::get_misc_class() );

		$qp   = new Helper_QueryPath();
		$html = $qp->html5( $field->html() );

		$this->assertSame( 1, $html->find( '.consent-accepted' )->count() );
		$this->assertSame( 1, $html->find( '.consent-accepted-label' )->count() );
		$this->assertSame( 1, $html->find( '.consent-text' )->count() );
	}

	public function test_maximum_allowed_css_each_field(){
		$form  = $GLOBALS['GFPDF_Test']->form['all-form-fields'];
		$entry = $GLOBALS['GFPDF_Test']->entries['all-form-fields'][0];

		$form_id          = GFAPI::add_form( $form );
		$entry['form_id'] = $form_id;
		$entry_id         = GFAPI::add_entry( $entry );

		/* Verify classes are truncated at 8 */
		$text_field = new GF_Field_Text( $form['fields'][0] );

		$field = new Field_Text( $text_field, GFAPI::get_entry( $entry_id ), GPDFAPI::get_form_class(), GPDFAPI::get_misc_class() );
		$array_css = explode( ' ', $field->get_field_classes() );

		$this->assertCount( 8, $array_css );

		$this->assertContains( 'exclude', $array_css );
		$this->assertContains( 'class7', $array_css );
		$this->assertContains( 'class4', $array_css );
		$this->assertNotContains( 'class8', $array_css );

		/* Verify nothing is truncated */
		$text_field = new GF_Field_Textarea( $form['fields'][1] );

		$field = new Field_Textarea( $text_field, GFAPI::get_entry( $entry_id ), GPDFAPI::get_form_class(), GPDFAPI::get_misc_class() );
		$array_css = explode( ' ', $field->get_field_classes() );

		$this->assertCount( 2, $array_css );

		$this->assertCount( 2, $array_css );
	}
}
