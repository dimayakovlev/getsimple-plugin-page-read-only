<?php
/*
Plugin Name: Page Read Only
Description: Limit ability to edit and save page via admin interface
Version: 1.0
Author: Dmitry Yakovlev
Author URI: http://dimayakovlev.ru/
*/

i18n_merge('PageReadOnly') || i18n_merge('PageReadOnly', 'en_US');

register_plugin(
  'PageReadOnly',
  'Page Read Only',
  '1.0',
  'Dmitry Yakovlev',
  'http://dimayakovlev.ru',
  i18n_r('PageReadOnly/DESCRIPTION'),
  '',
  ''
);

add_action('edit-extras', function() {
  global $data_edit;
  if (is_object($data_edit) && !empty($data_edit->readOnly)) {
?>
<script>
  $( document ).ready(function() {
    $("#editform :input").attr("disabled", true);
    $("#page_submit, #dropdown").remove();
    $('#editform').prepend('<div class="notify page-read-only-notify" style="display:block;"><?php i18n('PageReadOnly/NOTIFICATION'); ?></div>');
    $('.page-read-only-notify').fadeOut(500).fadeIn(500);
  })
</script>
<?php
  }
});

add_action('pages-main', function() {
  global $pagesArray;
  $pages = array();
  foreach($pagesArray as $page) {
    if (!empty($page['readOnly'])) $pages[] = $page['url'];
  }
  if (count($pages)) {
?>
<script type="text/javascript">
  $(document).ready(function() {
    var pages = <?php echo json_encode($pages); ?>;
    pages.forEach(function(page) {
      sup = " <sup>[<?php i18n('PageReadOnly/FILTER'); ?>]</sup>";
      $("#tr-" + page + " span.showstatus").append(sup);
      $("#tr-" + page + " .indexColumn").append(sup);
    });
  });
</script>
<?php
  }
});