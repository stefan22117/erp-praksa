/**
 * enables resizable data table columns.
 * Script by Ingo Hofmann
 * http://ihofmann.wordpress.com/2012/07/31/resizable-table-columns-with-jquery-ui/
 */
(function ($) {
    /**
     * Widget makes columns of a table resizable.
     */
    $.widget("ih.resizableColumns", {

        /**
         * initializing columns
         */
        _create: function() {
            this._initResizable();
        },

        /**
         * init jQuery UI sortable
         */
        _initResizable: function() {

            var colElement, colWidth, originalSize;
            var table = this.element;

            this.element.find("th").resizable({
                // use existing DIV rather than creating new nodes
                handles: {
                    "e": " .resizeHelper"
                },
   
                // default min width in case there is no label
                minWidth: 10,
                
                // set min-width to label size
                create: function(event, ui) {
                    var minWidth = $(this).find(".columnLabel").width();
                    if (minWidth) {
                        
                        // FF cannot handle absolute resizable helper
                        /*if ($.browser.mozilla) {
                            minWidth += $(this).find(".ui-resizable-e").width();
                        }*/
                        minWidth += $(this).find(".ui-resizable-e").width();
                        
                        $(this).resizable("option", "minWidth", minWidth);
                    }
                },

                // set correct COL element and original size
                start: function(event, ui) {
                    var colIndex = ui.helper.index() + 1;
                    colElement = table.find("colgroup > col:nth-child(" + colIndex + ")");
                    colWidth = parseInt(colElement.get(0).style.width, 10); // faster than width
                    originalSize = ui.size.width;
                },

                // set COL width
                resize: function(event, ui) {
                    var resizeDelta = ui.size.width - originalSize;

                    //Resize column 
                    var newColWidth = colWidth + resizeDelta;
                    colElement.width(newColWidth);

                    //Set new column width into data response
                    //console.log(newColWidth);
                    //console.log(ui.element.prop('id'));

                    var data = {type:"resize", changed: true, elementId:ui.element.prop('id')};
                    $(this).trigger(data);

                    // height must be set in order to prevent IE9 to set wrong height
                    $(this).css("height", "auto");                    
                }
            });
        }

    });
})(jQuery);