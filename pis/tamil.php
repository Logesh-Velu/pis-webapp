<!DOCTYPE html>
<html>
<head>
	<title>Tamil Font </title>
</head>
<body>

<input type="text" name="" id="tamil">

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript"> 
      // Load the Google Transliterate API
      google.load("elements", "1", {
      	packages: "transliteration"
      });
      
      function onLoad() {
      	var options = {
      		sourceLanguage:
      		google.elements.transliteration.LanguageCode.ENGLISH,
      		destinationLanguage:
      		[google.elements.transliteration.LanguageCode.TAMIL],
      		shortcutKey: 'ctrl+g',
      		transliterationEnabled: true
      	};
        // Create an instance on TransliterationControl with the required
        // options.
        var control =
        new google.elements.transliteration.TransliterationControl(options);
        
        // Enable transliteration in the textbox with id
        // 'transliterateTextarea'.
        control.makeTransliteratable(['tamil']);
    }
    google.setOnLoadCallback(onLoad);
    
</script> 



</body>
</html>