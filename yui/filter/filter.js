YUI.add('moodle-block_course_overview_plus-filter', function(Y) {

/**
 * 
 * Initialise this class by calling M.block_course_overview_plus.init
 */
var Filter = function() {
    Filter.superclass.constructor.apply(this, arguments);
};
Filter.prototype = {
    /**
     * Constructor for this class
     * @param {object} config
     */
    initializer : function() {
        var i;
        var nodeFilterYear = Y.one('#filterYear')
        if(nodeFilterYear != null) {nodeFilterYear.on('change', this.filterYear, this);}
        var nodeFilterTeacher = Y.one('#filterTeacher')
        if(nodeFilterTeacher != null)  {nodeFilterTeacher.on('change', this.filterTeacher, this);}
        var nodeFilterCategory = Y.one('#filterCategory')
        if(nodeFilterCategory != null) {nodeFilterCategory.on('change', this.filterCategory, this);}

    },
    filterYear : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
var index = Y.get("#filterYear").get('selectedIndex');
var value = Y.get("#filterYear").get("options").item(index).getAttribute('value');
if(value=="all") {
 Y.all('div.yeardiv').removeClass('cophidden');
} else {
 Y.all('div.yeardiv').addClass('cophidden');
 Y.all('div.copyear'+value).removeClass('cophidden');
}
   // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('courseoverviewplusselectedyear', value);
    },
    filterTeacher : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
var index = Y.get("#filterTeacher").get('selectedIndex');
var value = Y.get("#filterTeacher").get("options").item(index).getAttribute('value');
if(value=="all") {
 Y.all('div.teacherdiv').removeClass('cophidden');
} else {

 Y.all('div.teacherdiv').addClass('cophidden');
 Y.all('div.copteacher'+value).removeClass('cophidden');
}
   // Store the users selection (Uses AJAX to save to the database)
      M.util.set_user_preference('courseoverviewplusselectedteacher', value);
    },
    filterCategory : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
var index = Y.get("#filterCategory").get('selectedIndex');
var value = Y.get("#filterCategory").get("options").item(index).getAttribute('value');
if(value=="all") {
 Y.all('div.categorydiv').removeClass('cophidden');
} else {
 Y.all('div.categorydiv').addClass('cophidden');
 Y.all('div.copcategory'+value).removeClass('cophidden');
}
   // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('courseoverviewplusselectedcategory', value);
    }

};
Y.extend(Filter, Y.Base, Filter.prototype, {
    NAME : 'My Moodle Filter'
});
M.block_course_overview_plus = M.block_course_overview_plus || {};
M.block_course_overview_plus.initFilter = function() {
    return new Filter();
}

}, '@VERSION@', {requires:['base','node']});
