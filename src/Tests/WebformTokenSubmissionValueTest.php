<?php

namespace Drupal\webform\Tests;

use Drupal\webform\Entity\Webform;

/**
 * Tests for webform token submission value.
 *
 * @group Webform
 */
class WebformTokenSubmissionValueTest extends WebformTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['taxonomy'];

  /**
   * Webforms to load.
   *
   * @var array
   */
  protected static $testWebforms = ['test_token_submission_value'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create 'tags' vocabulary.
    $this->createTags();
  }

  /**
   * Test webform token submission value.
   */
  public function testWebformTokenSubmissionValue() {
    $webform = Webform::load('test_token_submission_value');

    // Check anonymous token handling.
    $this->postSubmission($webform);
    $tokens = [
      // Emails.
      'webform_submission:values:email' => 'example@example.com',
      'webform_submission:values:email:urlencode' => 'example%40example.com',
      'webform_submission:values:emails:0' => 'one@example.com',
      'webform_submission:values:emails:1' => 'two@example.com',
      'webform_submission:values:emails:2' => 'three@example.com',
      'webform_submission:values:emails:value:comma' => 'one@example.com, two@example.com, three@example.com',
      'webform_submission:values:emails:html' => '<div class="item-list"><ul><li><a href="mailto:one@example.com">one@example.com</a></li><li><a href="mailto:two@example.com">two@example.com</a></li><li><a href="mailto:three@example.com">three@example.com</a></li></ul></div>',
      'webform_submission:values:emails:0:html' => '<a href="mailto:one@example.com">one@example.com</a>',
      'webform_submission:values:emails:1:html' => '<a href="mailto:two@example.com">two@example.com</a>',
      'webform_submission:values:emails:2:html' => '<a href="mailto:three@example.com">three@example.com</a>',
      'webform_submission:values:emails:99:html' => '',

      // Users.
      'webform_submission:values:user' => 'admin (1)',
      'webform_submission:values:users' => 'admin (1)',
      'webform_submission:values:user:entity:mail' => 'admin@example.com',
      'webform_submission:values:users:0:entity:account-name' => 'admin',
      'webform_submission:values:users:99:entity:account-name' => '',

      // Current users.
      'current-user:display-name' => '',
      'current-user:missing' => '',

      // Terms.
      'webform_submission:values:term' => 'Parent 1 (1)',
      'webform_submission:values:terms' => 'Parent 1 (1), Parent 1: Child 1 (2)',
      'webform_submission:values:term:entity:name' => 'Parent 1',
      'webform_submission:values:terms:entity:name' => 'Parent 1',
      'webform_submission:values:terms:1:entity:name' => 'Parent 1: Child 1',

      // Names.
      'webform_submission:values:name' => 'John Smith',
      'webform_submission:values:names' => '- John Smith
- Jane Doe',
      'webform_submission:values:names:0' => 'John Smith',
      'webform_submission:values:names:1' => 'Jane Doe',
      'webform_submission:values:names:99' => '',

      // Contacts.
      'webform_submission:values:contact' => 'John Smith
10 Main Street
Springfield, Alabama. 12345
United States
john@example.com',
      'webform_submission:values:contacts' => '- John Smith
  10 Main Street
  Springfield, Alabama. 12345
  United States
  john@example.com
- Jane Doe
  10 Main Street
  Springfield, Alabama. 12345
  United States
  jane@example.com',
      'webform_submission:values:contacts:html' => '<div class="item-list"><ul><li>John Smith<br />10 Main Street<br />Springfield, Alabama. 12345<br />United States<br /><a href="mailto:john@example.com">john@example.com</a></li><li>Jane Doe<br />10 Main Street<br />Springfield, Alabama. 12345<br />United States<br /><a href="mailto:jane@example.com">jane@example.com</a></li></ul></div>',
      'webform_submission:values:contacts:0:html' => 'John Smith<br />10 Main Street<br />Springfield, Alabama. 12345<br />United States<br /><a href="mailto:john@example.com">john@example.com</a>',
      'webform_submission:values:contacts:0:name' => 'John Smith',
      'webform_submission:values:contacts:1:name' => 'Jane Doe',
      'webform_submission:values:contacts:0:email:html' => '<a href="mailto:john@example.com">john@example.com</a>',
      'webform_submission:values:contacts:0:email:urlencode' => 'john%40example.com',
      'webform_submission:values:contacts:1:email:raw:html' => 'jane@example.com',

      // Containers
      'webform_submission:values:fieldset' => '<pre>fieldset
--------
first_name: John
last_name: Smith
</pre>',
      'webform_submission:values:fieldset:urlencode' => 'fieldset%0A--------%0Afirst_name%3A+John%0Alast_name%3A+Smith%0A',

      // Submission limits.
      'webform_submission:limit:webform' => '100',
      'webform_submission:total:webform' => '1',
      'webform_submission:limit:user' => '10',
      'webform_submission:total:user' => '1',
      'webform_submission:limit:webform:source_entity' => '50',
      'webform_submission:total:webform:source_entity' => '',
      'webform_submission:limit:user:source_entity' => '5',
      'webform_submission:total:user:source_entity' => '',

      // Clear.
      'webform_submission:values:missing' => '[webform_submission:values:missing]',
      'webform_submission:values:missing:clear' => '',
    ];
    foreach ($tokens as $token => $value) {
      $this->assertRaw("<tr><th width=\"50%\">$token</th><td width=\"50%\">$value</td></tr>");
    }

    // Check containers.
    $this->assertRaw('<tr><th width="50%">webform_submission:values:fieldset</th><td width="50%"><pre>fieldset');
    $this->assertRaw('<tr><th width="50%">webform_submission:values:fieldset:html</th><td width="50%"><fieldset class="webform-container webform-container-type-fieldset js-form-item form-item js-form-wrapper form-wrapper" id="test_token_submission_value--fieldset">');
    $this->assertRaw('<tr><th width="50%">webform_submission:values:fieldset:header:html</th><td width="50%"><section id="test_token_submission_value--fieldset" class="js-form-item form-item js-form-wrapper form-wrapper webform-section">');
    $this->assertRaw('<tr><th width="50%">webform_submission:values:fieldset:details:html</th><td width="50%"><details data-webform-element-id="test_token_submission_value--fieldset" class="webform-container webform-container-type-details js-form-wrapper form-wrapper" id="test_token_submission_value--fieldset" open="open">');
    $this->assertRaw('<tr><th width="50%">webform_submission:values:fieldset:fieldset:html</th><td width="50%"><fieldset class="webform-container webform-container-type-fieldset js-form-item form-item js-form-wrapper form-wrapper" id="test_token_submission_value--fieldset">');

    // Check authenticated token handling.
    $this->drupalLogin($this->rootUser);
    $this->postSubmission($webform);
    $tokens = [
      // Current users.
      'current-user:display-name' => 'admin',
      'current-user:missing' => '',
    ];
    foreach ($tokens as $token => $value) {
      $this->assertRaw("<tr><th width=\"50%\">$token</th><td width=\"50%\">$value</td></tr>");
    }
  }

}
