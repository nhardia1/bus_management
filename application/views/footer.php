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
            [google.elements.transliteration.LanguageCode.HINDI],
        shortcutKey: 'ctrl+g',
        transliterationEnabled: true
    };

    // Create an instance on TransliterationControl with the required
    // options.
    var control =
        new google.elements.transliteration.TransliterationControl(options);

    // Enable transliteration in the textbox with id
    // 'transliterateTextarea'.
    control.makeTransliteratable(['csbox']);
  }
  google.setOnLoadCallback(onLoad);
</script>


<div align="center" class="col-md-12">
	<div class="panel-body">
		Copyright &#169;&nbsp;<?php echo date("Y");?>
	</div>
</div>	


		</section>

    </section>
              
	</div>

</div>


          
    
  </body>

</html>