(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  // On Document Ready...

  jQuery(document).on("nfFormReady", function () {
    const signatureFieldProcess = function () {
      let $ = jQuery,
        $this = $(this),
        $canvas = $this.find(".signature").first(),
        tCtx = $canvas[0].getContext("2d"),
        signaturePad = new SignaturePad($canvas[0]),
        $hiddenInput = $this.find("input[data-type=signature]"),
        $clearButton = $this.find(".clear-signature"),
        $links = $this.find(".links a"),
        $typeField = $this.find(".type-field");
      $this.addClass("applied");
      signaturePad.addEventListener("endStroke", () => {
        $hiddenInput.val(signaturePad.toDataURL());
      });
      $clearButton.on("click", function (e) {
        e.preventDefault();
        $hiddenInput.val("");
        signaturePad.clear();
      });

      $typeField.focus(function (e) {
        signaturePad.clear();
        $hiddenInput.val("");
      });

      $typeField.blur(function (e) {
        signaturePad.clear();
        tCtx.font = "70px Kalam";
        tCtx.fillStyle = "black";
        tCtx.fillText($typeField.val(), 20, 80);
        $hiddenInput.val(tCtx.canvas.toDataURL());
      });
      $links.on("click", function (e) {
        e.preventDefault();
        let $this = $(this),
          text = $this.text();
        console.log(text);
        $canvas.addClass("hidden");
        $clearButton.addClass("hidden");
        $typeField.removeClass("hidden");

        if (text == "Draw") {
          $canvas.removeClass("hidden");
          $clearButton.removeClass("hidden");
          $typeField.addClass("hidden");
        }
      });
    };
    if (typeof Marionette !== "undefined") {
      let myConditionalLogicController = Marionette.Object.extend({
        initialize: function () {
          this.listenTo(
            Backbone.Radio.channel("fields"),
            "change:model",
            this.fieldModelChange
          );
        },

        fieldModelChange: function (fieldModel) {
          if (
            fieldModel.changed.visible === true &&
            fieldModel.attributes.parentType == "signature"
          ) {
            jQuery(".signature-field")
              .not(".applied")
              .each(signatureFieldProcess);
          }

          // code to be run when a conditional is field is shown
        },
      });
      new myConditionalLogicController();
    }
    jQuery(".signature-field").not(".applied").each(signatureFieldProcess);

    // var signaturePad = new SignaturePad(canvas);
  });
})(jQuery);
