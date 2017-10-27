/**
 * jQuery printPage Plugin
 * @version: 1.0
 * @author: Cedric Dugas, http://www.position-absolute.com
 * @licence: MIT
 * @desciption: jQuery page print plugin help you print your page in a better way
 */

/*(function( $ ){
  $.fn.printPage = function(options) {
    // EXTEND options for this button
    var pluginOptions = {
      attr : "href",
      url : false,
      message: "Please wait while we create your document" 
    };
    $.extend(pluginOptions, options);

    this.on("click", function(){  loadPrintDocument(this, pluginOptions); return false;  });
    
    *
     * Load & show message box, call iframe
     * @param {jQuery} el - The button calling the plugin
     * @param {Object} pluginOptions - options for this print button
     */   
    function loadPrintDocument(el, pluginOptions){
      $("body").append(components.messageBox(pluginOptions.message));
      $("#printMessageBox").css("opacity", 0);
      $("#printMessageBox").animate({opacity:1}, 300, function() { addIframeToPage(el, pluginOptions); });
    }
    /**
     * Inject iframe into document and attempt to hide, it, can't use display:none
     * You can't print if the element is not dsplayed
     * @param {jQuery} el - The button calling the plugin
     * @param {Object} pluginOptions - options for this print button
     */
    function addIframeToPage(el, pluginOptions){

        var url = (pluginOptions.url) ? pluginOptions.url : $(el).attr(pluginOptions.attr);

        if(!$('#printPage')[0]){
          $("body").append(components.iframe(url));
          $('#printPage').on("load",function() {  printit();  })
        }else{
          $('#printPage').attr("src", url);
        }
    }
    /*
     * Call the print browser functionnality, focus is needed for IE
     */
    function printit(){
      frames["printPage"].focus();
      frames["printPage"].print();
      unloadMessage();
    }
    /*
     * Hide & Delete the message box with a small delay
     */
    function unloadMessage(){
      $("#printMessageBox").delay(1000).animate({opacity:0}, 700, function(){
        $(this).remove();
      });
    }
    /*
     * Build html compononents for thois plugin
     */
    var components = {
      iframe: function(url){
        return '<iframe id="printPage" name="printPage" src='+url+' style="position:absolute;top:0px; left:0px;width:0px; height:0px;border:0px;overfow:none; z-index:-1"></iframe>';
      },
      messageBox: function(message){
        return "<div id='printMessageBox' style='\
          position:fixed;\
          top:50%; left:50%;\
          text-align:center;\
          margin: -60px 0 0 -155px;\
          width:310px; height:120px; font-size:16px; padding:10px; color:#222; font-family:helvetica, arial;\
          opacity:0;\
          background:#fff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAUeSURBVFhHxVhLiBxVFD3iF1y4EMGFoiB+UBHFPwriIkHRhTsVkQQ0qCi4NiKIuHAlKKIEZpzMdFVXzz8ziRoECYoIUVQ0jCYhxihRtyagZibT9TznVlV3dU1XdVWnJjNwp6u77r3vvHvf/bwLlPgLR3BzOIMPQh/ToUfyMdsmhU3MtD3M8ftCD3mYt3cJHz+dj6mwgRb5fD7vbE/jTfceLi+xfDELAb3hpvG3m4RzQQ61+Hua8vjSv1MfN/2H87B1aJDtBl53e7m4wM2QFjK02Oe3hKfonXimujrDAI9WBkmhK+mOZQNHZXz+ke5s0qLjfB4jjZJG+H20L+ldxCPeLnn4kFbbSZnfDeQ8HPUePPwOLqwEkkqfcLICLcfnA2FVBQNWowGuCSdxwkDK9T5uqAbQwzPm0l0GcFcl4RLMzuEcBs0xNxudXwbVbSXEuiwU2JYCuKeScAlmN4aLuPHf7GwrwHzcXkIsBdDHcx2AHhYrCZdg1pEhwGMGUBHdwJ0lxHoAvpCy4EIl4RLMPHsXMFB+7QD0cVcJsS7LqocXLUjW6wzuwPkEeNTOoLJEE3dXAkjzv9QByMpQSbgEs4sA/mIWFMAW7ikhlnJxg2dwN4VpRSqarCRckpl6DycWZG68daCYA0Nf+amJmxhVb8m9bs7SzD7Svfzt/jqIwO6jvgf4eZxlNLKgh6e1LsvfVX2BkuFxvvyBAFdc08K+S0qkSqhSVhdJX3odPZPaAU6F0/iKeB7sAF1t4Fk7byw7nYYgCzJWYErrIhlBm9emlQv1rE8WCBprlcbaDP67jub+x5i8jOXSVqz7WWupjHoEooStTScY9I7G4u8HoUJu7jub4LRZgmHk/qzgsKqic9li4hbI2BjEtiKAX8fFuvfc1W2xrD4Fn9fbZrkJbLXikAD0cBpkOrAmKNYbnNwp947jjnTEhhN4yDJHF+CqLLh01gEKgM5YgPEegE0sKq31AtwICyYeYpmjB7+gkd7m535L2invKYA2xsXpIySLKcVlwAloBNDH94VBouhW2y8ldZKCQXmvIHsQoAXJd4UA1aux6yC9z83o6lkPedhBnSfiZrVvBokA+vi2EKCyOu+zAwv5EAwEcMi6mZyskVhwfyFA9YO8nA+xfqGI24fzqPdIGYDFLs40rCdHcRlvY1vYeWyhZcuT+H089dcELhbyuB88mq4cWUtSv0XxXguCvOScAbiiluvjOGCyF/mi73sow5L670jUTpUCaFGsHszHf7k72QiAiuyoFB6xc0KALzvtUDlJoZ+0U7JqBuApH9eyqLe4sYAKiikaFkUUYJIThAbL26V9LZisqfXjRplefaxzkDXEYQ+2ZGGf9GfaSRQk63ftTLoXHbOow2m3p/BNOIZH1kQZz8i54SxHbWx9uNt3rYHNAOTBvZFBovIk+jyX5vElrfxkXiivuRfzFql1GdXlRiBk3maXpsjFu5OFVqTko9gN2kAefWZyr+UBjCcL0cU9unbeUimVZe7FndGHAVTJ03lV/cyjTy3BvzoAYDT6GOpe7OH5frMZKruELt7EHW9i/5ZPc9hMK11dCuBQow+5OM5rdHf9ow9Nt7wzGR7xGmoA1fk2sURrWQWo64/uv54Re3L4+WADV/CQLyf3YUb1T6RANDD/FfH4NqUNqPvPZMJqXT2HSZU3T8Ht7pO4H+w3o65S6rK8yYw6SmMPVwaXCFD4Fd70j2viEN9hTxP4mZHuwgGWWRgO0dW5uVIY/gcU4PbAMXwbwwAAAABJRU5ErkJggg==) center 40px no-repeat;\
          border: 2px solid #333;\
          border-radius:6px; -webkit-border-radius:6px; -moz-border-radius:6px;\
          box-shadow:0px 0px 6px #888; -webkit-box-shadow:0px 0px 6px #888; -moz-box-shadow:0px 0px 6px #888'>\
          "+message+"</div>";
      }
    }
 /* };
})( jQuery );
*/