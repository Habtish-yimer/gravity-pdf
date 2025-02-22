import { Selector, t } from 'testcafe'
import { admin, baseURL } from '../../../auth'
import { link, listItem } from '../helpers/field'
import Pdf from '../helpers/pdf'

const pdf = new Pdf()

class AdvancedCheck {
  constructor () {
    // Shortcode section
    this.confirmationTextCheckbox = Selector('#gform-settings-radio-choice-type0').find('input').withAttribute('id', 'type0')
    this.confirmationPageCheckbox = Selector('#gform-settings-radio-choice-type1').find('input').withAttribute('id', 'type1')
    this.confirmationRedirectCheckbox = Selector('#gform-settings-radio-choice-type2').find('input').withAttribute('id', 'type2')
    this.shortcodeBox = Selector('button').withAttribute('aria-label', 'Copy the Sample PDF shortcode to the clipboard')
    this.confirmationPageSelectBox = Selector('#gform_setting_page').find('button#gform-page-control')
    this.queryStringInputBox = Selector('#gform_setting_queryString').find('[id="queryString"]')
    this.wysiwgEditorTextTab = Selector('#gform-settings-section-confirmations').find('.wp-editor-tabs').find('button').withText('Text')
    this.wysiwgEditor = Selector('div').find('textarea[class^="merge-tag-support"]')
    this.redirectInputBox = Selector('#gform_setting_url').find('[id="url"]')
    this.previewLink = Selector('#gf_toolbar_buttons_container a').addCustomDOMProperties({
      href: el => el.href
    })
    this.saveConfirmationButton = Selector('.gform-settings-save-container').find('button').withText('Save Confirmation')
    this.formInputField = Selector('input').withAttribute('name', 'input_1')
    this.submitButton = Selector('input').withAttribute('value', 'Submit')

    // General Settings - Debug Mode field
    this.debugModeCheckbox = Selector('#gfpdf-fieldset-debug_mode').find('[id="gfpdf_settings[debug_mode]"]')
    this.debugModeErrorMessage = Selector('div').withText('PDF link not displayed because PDF is inactive.')

    // Merge tags section
    this.mergeTagsButton = Selector('#gform_setting_message').find('button.gform-dropdown__control')
    this.textInputField = Selector('input').withAttribute('name', 'input_1')
    this.fNameInputField = Selector('.name_first').find('input[type^="text"]')
    this.lNameInputField = Selector('.name_last').find('input[type^="text"]')
    this.emailInputField = Selector('input').withAttribute('name', 'input_3')

    // PDF restriction section
    this.pdfRestrictionErrorMessage = Selector('div').withAttribute('class', 'wp-die-message').withText('You do not have access to view this PDF.')
    this.viewEntryLink = Selector('.entry_unread').find('a.gravitypdf-download-link').nth(1)
    this.wpLoginForm = Selector('#login').find('form').withAttribute('name', 'loginform')

    // Pdf template section
    this.addNewButton = Selector('.tablenav').find('a').withText('Add New')
    this.pdfLabelNameInputBox = Selector('#gfpdf-fieldset-gfpdf_form_settings_general').find('[id="gfpdf_settings[name]"]')
    this.pdfFilenameInputBox = Selector('#gfpdf-fieldset-gfpdf_form_settings_general').find('[id="gfpdf_settings[filename]"]')
    this.addUpdatePdfButton = Selector('#submit-and-promo-container').find('[id="submit"]')
    this.templateItem = Selector('#the-list').find('tr')
    this.pdfListSection = Selector('.gform-settings__navigation').find('a').withText('PDF')
    this.toggleSwitch = Selector('#the-list').find('.check-column button')
    this.entryItemSection = Selector('#the-list').find('a').withAttribute('aria-label', 'View this entry')
    this.viewPdfLink = Selector('#the-list').find('a').withText('View PDF')
    this.editLink = Selector('#the-list').find('span').withText('Edit')
    this.conditionalLogicCheckbox = Selector('#gfpdf-fieldset-gfpdf_form_settings_general').find('[id="gfpdf_conditional_logic"]')
    this.templateDetail = Selector('#the-list').find('tr').nth(0).find('td').nth(0)
    this.duplicateLink = Selector('#the-list').find('a').withText('Duplicate')
    this.deletePDF = Selector('.submitdelete')
  }

  async copyDownloadShortcode (uri) {
    await pdf.navigate(uri)
    await t
      .click(this.shortcodeBox)
  }

  async navigateConfirmationSection (uri) {
    await pdf.navigate(uri)

    await t
      .click(link('#the-list', 'Default Confirmation'))
  }

  async navigateLink (uri) {
    await pdf.navigate(uri)
  }

  async navigateSection (uri) {
    await pdf.navigate(uri)
  }

  async pickMergeTag (text) {
    await t
      .click(this.mergeTagsButton)
      .click(listItem(text))
      .pressKey('enter')
  }

  async toggleRestrictOwnerCheckbox (uri) {
    await pdf.navigate(uri)

    await t
      .click(Selector('td.name').find('a').withText('Sample'))
      .click(pdf.restrictOwnerCheckbox)
      .click(pdf.saveSettings)
  }

  async submitNewPdfEntry () {
    await t
      .typeText(this.textInputField, 'texttest', { paste: true })
      .click(this.submitButton)
      .wait(500)
  }

  async navigateAddPdf (uri) {
    await pdf.navigate(uri)

    await t
      .click(this.addNewButton)
      .typeText(this.pdfLabelNameInputBox, 'Test PDF Template', { paste: true })
      .typeText(this.pdfFilenameInputBox, 'testpdftemplate', { paste: true })
      .click(this.addUpdatePdfButton)
      .click(this.pdfListSection)
  }

  async navigateDeletePdfEntries (uri) {
    await pdf.navigate(uri)

    const template = await this.templateItem.count
    if (template > 0) {
      for (let i = 0; i < template; i++) {
        await t
          .hover(this.templateItem)
          .click(this.deletePDF)
          .wait(500)
      }
    }
  }
}

export default AdvancedCheck
