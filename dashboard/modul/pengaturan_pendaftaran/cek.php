<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex, nofollow">
  <title>Paste from Word</title>
  <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
</head>

<body>
  <style type="text/css">
    /* Minimal styling to center the editor in this sample */
    .container {
      padding: 30px;
      display: flex;
      align-items: center;
      text-align: center;
    }

    .inner-container {
      margin: 0 auto;
    }
  </style>
  <textarea cols="80" id="editor1" name="editor1" rows="10">&lt;p&gt;This is some &lt;strong&gt;sample text&lt;/strong&gt;. You are using &lt;a href=&quot;https://ckeditor.com/&quot;&gt;CKEditor&lt;/a&gt;.&lt;/p&gt;</textarea>
  <script>
    CKEDITOR.replace('editor1', {
      // Make the editing area bigger than default.
      height: 1000,
      width: 760,

      // Allow pasting any content.
      allowedContent: true,

      // Fit toolbar buttons inside 3 rows.
      toolbarGroups: [{
          name: 'document',
          groups: ['mode', 'document', 'doctools']
        },
        {
          name: 'clipboard',
          groups: ['clipboard', 'undo']
        },
        {
          name: 'editing',
          groups: ['find', 'selection', 'spellchecker', 'editing']
        },
        {
          name: 'forms',
          groups: ['forms']
        },
        '/',
        {
          name: 'paragraph',
          groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
        },
        {
          name: 'links',
          groups: ['links']
        },
        {
          name: 'insert',
          groups: ['insert']
        },
        '/',
        {
          name: 'styles',
          groups: ['styles']
        },
        {
          name: 'basicstyles',
          groups: ['basicstyles', 'cleanup']
        },
        {
          name: 'colors',
          groups: ['colors']
        },
        {
          name: 'tools',
          groups: ['tools']
        },
        {
          name: 'others',
          groups: ['others']
        },
        {
          name: 'about',
          groups: ['about']
        }
      ],

      // Remove buttons irrelevant for pasting from external sources.
      removeButtons: 'ExportPdf,Form,Checkbox,Radio,TextField,Select,Textarea,Button,ImageButton,HiddenField,NewPage,CreateDiv,Flash,Iframe,About,ShowBlocks,Maximize',

      // An array of stylesheets to style the WYSIWYG area.
      // Note: it is recommended to keep your own styles in a separate file in order to make future updates painless.
      contentsCss: [
        'https://cdn.ckeditor.com/4.22.1/full-all/contents.css?t=N5UC',
        'https://ckeditor.com/docs/ckeditor4/latest/examples/assets/css/pastefromword.css?t=N5UC'
      ],

      // This is optional, but will let us define multiple different styles for multiple editors using the same CSS file.
      bodyClass: 'document-editor'
    });
  </script>
</body>

</html>